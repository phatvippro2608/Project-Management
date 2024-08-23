<?php

namespace App\Http\Controllers;

use App\Models\CostComissionModel;
use App\Models\CostModel;
use App\Models\CostGroupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\ProjectModel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\SpreadsheetModel;
use Illuminate\Support\Facades\Log;
use App\Models\MaterialsModel;

class ProjectBudgetController extends Controller
{


    public function getView(Request $request, $id)
    {
        $index = $request->input('location', '');
        if(empty($index)){
            $dataCost = DB::table('project_costs')
            ->join('project_cost_group', 'project_costs.project_cost_group_id', '=', 'project_cost_group.project_cost_group_id')
            ->select('project_costs.*', 'project_cost_group.project_cost_group_name')->where('project_id', $id)
            ->get();
            $dataCostGroup = DB::table('project_cost_group')
            ->join('project_locations', 'project_cost_group.project_location_id', '=', 'project_locations.project_location_id')
            ->join('projects', 'project_locations.project_id', '=', 'projects.project_id')
            ->select('project_cost_group.*', 'projects.project_name')
            ->where('projects.project_id', $id)->get();
        }
        else{
            $dataCost = DB::table('project_costs')
            ->join('project_cost_group', 'project_costs.project_cost_group_id', '=', 'project_cost_group.project_cost_group_id')
            ->join('project_locations', 'project_cost_group.project_location_id', '=', 'project_locations.project_location_id')
            ->select('project_costs.*', 'project_cost_group.project_cost_group_name')
            ->where('project_costs.project_id', $id)
            ->where('project_cost_group.project_location_id', $index)
            ->get();
            $dataCostGroup = DB::table('project_cost_group')->where('project_location_id', $index)->get();
        }
        $contingency_price = DB::table('projects')->where('project_id', $id)->first();

        $total=0;
            $subtotal2=0;
            $chart=[];
            foreach($dataCostGroup as $group){
                $subtotal1=0;
                foreach ($dataCost as $data) {
                    if ($data->project_id == $id && $data->project_cost_group_id == $group->project_cost_group_id){
                        $subtotal2 = $data->project_cost_labor_qty *
                                    $data->project_cost_budget_qty *
                                    ($data->project_cost_labor_cost +
                                        $data->project_cost_misc_cost +
                                        $data->project_cost_ot_budget +
                                        $data->project_cost_perdiempay);
                        $subtotal1 += $subtotal2;
                    }
                    }
                $chart[$group->project_cost_group_name] = $subtotal1;
                $total += $subtotal1;
            }
        return view('auth.project-budget.budget-detail', [
            'dataCost' => $dataCost,
            'dataCostGroup' => $dataCostGroup,
            'id' => $id,
            'contingency_price' => $contingency_price,
            'total' => $total,
            'chart' => $chart,
            'location' => $index
        ]);
    }

    public function budget_import(Request $request, $id) {
        try {
            // Read Excel data
            $dataExcel = SpreadsheetModel::readExcel($request->file('file'));
            $location = $request->input('location', '');
            // Fetch project name
            $project = DB::table('projects')->select('project_name')
                ->where('project_id', $id)
                ->first();

            $prjNameCurrent = $project->project_name;
            $prjName = $dataExcel['data'][1][1];
            $prjLocationName = $dataExcel['data'][2][1];
            $prjLocationId = DB::table('project_locations')->select('project_location_id')->where('project_id', $id)->where('project_location_name', $prjLocationName)->first();

            if ($prjNameCurrent != $prjName) {
                return response()->json([
                    'success' => false,
                    'message' => 'why did you import project ' . $prjNameCurrent . ' instead on project ' . $prjLocationName,
                ]);
            }

            // if ($prjLocationName != $prjLocationNameCurrent) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'why did you import location ' . $prjLocationName . ' instead on location ' . $prjLocationNameCurrent,
            //     ]);
            // }

            $num_row = 4; // Start reading from row 3
            while ($num_row < count($dataExcel['data'])) {
                if (strpos(trim($dataExcel['data'][$num_row][0]), "NAME: ") !== false) {
                    $name = str_replace("NAME: ", "", trim($dataExcel['data'][$num_row][0]));

                    // Insert a new cost group and get its ID
                    $group_id = DB::table('project_cost_group')->insertGetId([
                        'project_cost_group_name' => $name,
                        'project_location_id' => $prjLocationId
                    ]);
                } else {
                    $description = trim($dataExcel['data'][$num_row][0] ?? '');
                    $laborQTY = trim($dataExcel['data'][$num_row][1] ?? '');
                    $laborUnit = trim($dataExcel['data'][$num_row][2] ?? '');
                    $budgetQTY = trim($dataExcel['data'][$num_row][3] ?? '');
                    $budgetUnit = trim($dataExcel['data'][$num_row][4] ?? '');
                    $laborCost = trim($dataExcel['data'][$num_row][5] ?? '');
                    $miscCost = trim($dataExcel['data'][$num_row][6] ?? '');
                    $otBudget = trim($dataExcel['data'][$num_row][7] ?? '');
                    $perDiemPay = trim($dataExcel['data'][$num_row][8] ?? '');
                    $remark = trim($dataExcel['data'][$num_row][9] ?? '');

                    // Check if all required fields are not empty
                    if (!empty($description) || !empty($laborQTY) || !empty($laborUnit) ||
                        !empty($budgetQTY) || !empty($budgetUnit) || !empty($laborCost) ||
                        !empty($miscCost) || !empty($otBudget) || !empty($perDiemPay) || !empty($remark)) {

                        // Insert project costs
                        DB::table('project_costs')->insert([
                            'project_id' => $id,
                            'project_cost_description' => $description,
                            'project_cost_labor_qty' => intval($laborQTY),
                            'project_cost_labor_unit' => $laborUnit,
                            'project_cost_budget_qty' => intval($budgetQTY),
                            'project_budget_unit' => $budgetUnit,
                            'project_cost_labor_cost' => intval($laborCost),
                            'project_cost_misc_cost' => intval($miscCost),
                            'project_cost_perdiempay' => intval($perDiemPay),
                            'project_cost_ot_budget' => intval($otBudget),
                            'project_cost_remaks' => $remark,
                            'project_cost_group_id' => $group_id,
                            'create_date' => today()
                        ]);
                    }
                    $num_row++;
                    continue;
                }

                $num_row++;
            }
            return response()->json([
                'success' => true,
                'message' => 'Import successfully!',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage(),
            ]);
        }


    }

    public function showProjects()
    {

        $submenu = DB::table('projects')->get();
        return view('auth.show-projects', ['submenu' => $submenu]);
    }

    public function update(Request $request, $id)
{
    // Validate the request
    $request->validate([
        'project_name' => 'nullable|string',
        'project_description' => 'nullable|string'
    ]);

    try {
        // Update the project using raw SQL
        DB::table('projects')->where('project_id', $id)->update([
            'project_name' => $request->input('project_name'),
            'project_description' => $request->input('project_description')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully!',
        ]);
    } catch (\Exception $e) {
        // Log the error and return an error response
        \Log::error('Error updating project: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error updating project.',
        ]);
    }
}

public function showProjectDetail(Request $request,$id)
{
    $keyword = $request->input('location', '');
    AccountController::setRecentProject($id);
    $data = DB::table('projects')->where('project_id', $id)->first();
    $prj = ProjectModel::find($id);
    if ($prj){
        // Gọi phương thức getCustomer để lấy thông tin khách hàng
        $customer = $prj->getCustomer();
        $contract = $prj->getContract();
        $contactEmployee = $prj->getEmployee();
    }
    $total = 0;
    $subtotal1 = 0;
    $items = DB::table('project_costs')->where('project_id', $id)->get();

    if(!empty($keyword)){
    //get data if choose location
        $dataLoca = DB::table('project_locations')
        ->join('projects', 'projects.project_id','=','project_locations.project_id')
        ->select('projects.*', 'project_locations.*')->where('project_locations.project_id', $id)->where('project_locations.project_location_id', $keyword)->first();
        $dataCost = DB::table('project_costs')
            ->join('project_cost_group', 'project_costs.project_cost_group_id', '=', 'project_cost_group.project_cost_group_id')
            ->join('project_locations', 'project_cost_group.project_location_id', '=', 'project_locations.project_location_id')
            ->select('project_costs.*', 'project_cost_group.project_cost_group_name')
            ->where('project_costs.project_id', $id)
            ->where('project_cost_group.project_location_id', $keyword)
            ->get();
            $dataCostGroup = DB::table('project_cost_group')->where('project_location_id', $keyword)->get();

        //get data material costs
        $materialData = DB::table('project_materials')
                ->join('project_locations', 'project_materials.project_location_id', '=', 'project_locations.project_location_id')
                ->join('projects', 'projects.project_id', '=', 'project_locations.project_id')
                ->select('project_materials.*', 'project_locations.*')
                ->where('projects.project_id', $id)->where('project_locations.project_location_id', $keyword)->get();
    }else{
    //get data if choose see all, dont have location
        $dataLoca = DB::table('project_locations')
        ->join('projects', 'projects.project_id','=','project_locations.project_id')
        ->select('projects.*', 'project_locations.*')->where('project_locations.project_id', $id)->first();
        $dataCost = DB::table('project_costs')
            ->join('project_cost_group', 'project_costs.project_cost_group_id', '=', 'project_cost_group.project_cost_group_id')
            ->select('project_costs.*', 'project_cost_group.project_cost_group_name')->where('project_id', $id)
            ->get();
        $dataCostGroup = DB::table('project_cost_group')->get();

        $materialData = DB::table('project_materials')
                ->join('project_locations', 'project_materials.project_location_id', '=', 'project_locations.project_location_id')
                ->join('projects', 'projects.project_id', '=', 'project_locations.project_id')
                ->select('project_materials.*', 'project_locations.*', 'projects.project_name')
                ->where('projects.project_id', $id)->get();
    }
    $locations = DB::table('project_locations')
    ->join('projects', 'projects.project_id','=','project_locations.project_id')
    ->select('projects.*', 'project_locations.*')->where('project_locations.project_id', $id)->get();
    $contingency_price = DB::table('projects')->where('project_id', $id)->first();
    $total=0;
        $subtotal2=0;
        $chart=[];
        foreach($dataCostGroup as $group){
            $subtotal1=0;
            foreach ($dataCost as $data) {
                if ($data->project_id == $id && $data->project_cost_group_id == $group->project_cost_group_id){
                    $subtotal2 = $data->project_cost_labor_qty *
                                $data->project_cost_budget_qty *
                                ($data->project_cost_labor_cost +
                                    $data->project_cost_misc_cost +
                                    $data->project_cost_ot_budget +
                                    $data->project_cost_perdiempay);
                    $subtotal1 += $subtotal2;
                }
                }
            $chart[$group->project_cost_group_name] = $subtotal1;
            $total += $subtotal1;
        }
        $data = DB::table('projects')->where('project_id', $id)->first();

        $subtotal1 = [];
        $subvat1 = [];
        foreach ($materialData as $item) {
            $subtotal1[] = $item->project_material_quantity * $item->project_material_unit_price;
            $subvat1[] = $item->project_material_quantity * $item->project_material_unit_price * ($item->project_material_vat / 100);
        }
        $total1 = array_sum($subtotal1);
        $vat1 = array_sum($subvat1);
        $materialCost = $total1 - $vat1;
//    dd($dataLoca);
    return view('auth.project-budget.project-budget', [
        'data' => $data,
        'id' => $id,
        'total' => $total,
        'contract' => $contract,
        'contactEmployee' => $contactEmployee,
        'customer' => $customer,
        'locations' => $locations,
        'dataLoca' => $dataLoca,
        'keyword' => $keyword,
        'contingency_price' => $contingency_price,
        'materialCost' => $materialCost
    ]);
}




    public function getBudgetData($id, $costGroupId, $costId)
    {
        // Kiểm tra tham số
        if (!is_numeric($id) || !is_numeric($costGroupId)) {
            return response()->json(['error' => 'Tham số không hợp lệ'], 400);
        }

        try {
            // Lấy dữ liệu ngân sách
            $budgetData = DB::table('project_costs')
            ->where('project_cost_id', $costId)
            ->get();
            return response()->json($budgetData);
        } catch (\Exception $e) {
            // Xử lý lỗi và ghi nhật ký
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Đã xảy ra lỗi khi xử lý yêu cầu'], 500);
        }
    }
    public function updateBudget(Request $request, $id, $costGroupId, $costId)
{
    // Kiểm tra tham số
    if (!is_numeric($id) || !is_numeric($costGroupId) || !is_numeric($costId)) {
        return response()->json(['error' => 'Tham số không hợp lệ'], 400);
    }

    // Validate dữ liệu đầu vào
    $validatedData = Validator::make($request->all(), [
        'description' => 'required|string|max:255',
        'labor_qty' => 'nullable|numeric|min:0',
        'labor_unit' => 'nullable|string|max:100',
        'budget_qty' => 'nullable|numeric|min:0',
        'budget_unit' => 'nullable|string|max:100',
        'labor_cost' => 'nullable|numeric|min:0',
        'misc_cost' => 'nullable|numeric|min:0',
        'ot_budget' => 'nullable|numeric|min:0',
        'perdiem_pay' => 'nullable|numeric|min:0',
        'remarks' => 'nullable|string|max:255'
    ]);

    if ($validatedData->fails()) {
        return response()->json([
            'errors' => $validatedData->errors()
        ], 422);
    }

    // Dữ liệu cần cập nhật
    $data = [
        'project_cost_description' => $request->input('description'),
        'project_cost_labor_qty' => $request->input('labor_qty'),
        'project_cost_labor_unit' => $request->input('labor_unit'),
        'project_cost_budget_qty' => $request->input('budget_qty'),
        'project_budget_unit' => $request->input('budget_unit'),
        'project_cost_labor_cost' => $request->input('labor_cost'),
        'project_cost_misc_cost' => $request->input('misc_cost'),
        'project_cost_ot_budget' => $request->input('ot_budget'),
        'project_cost_perdiempay' => $request->input('perdiem_pay'),
        'project_cost_remaks' => $request->input('remarks')
    ];

    try {
        // Cập nhật dữ liệu ngân sách
        DB::table('project_costs')
            ->where('project_cost_id', $costId)
            ->update($data);

        return response()->json(['success' => 'Budget updated successfully']);
    } catch (\Exception $e) {
        // Xử lý lỗi và ghi nhật ký
        \Log::error($e->getMessage());
        return response()->json(['error' => 'Đã xảy ra lỗi khi xử lý yêu cầu'], 500);
    }
}
    public function deleteBudget($project_id, $cost_id)
    {
        try {
            DB::table('project_costs')->where('project_cost_id', $cost_id)->delete();
            return response()->json(['success' => true, 'message' => 'Cost item deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete cost item.']);
        }
    }



public function createCostGroup(Request $request, $id)
{
    $request->validate([
        'newGroupName' => 'required|string|max:255',
    ]);
    $location = $request->input('location', '');
    $newGroup = new CostGroupModel();
    $newGroup->project_cost_group_name = $request->input('newGroupName');
    $newGroup->project_location_id = $location;
    $newGroup->save();

    return response()->json([
        'success' => true,
        'message' => 'New cost group added successfully.'
    ]);
}

public function getCostGroupDetails(Request $request, $id, $group_id)
{
    $costGroup = CostGroupModel::find($group_id);

    if ($costGroup) {
        $html = '
        <div class="form-group">
            <label for="description">DESCRIPTION</label>
            <input type="text" class="form-control" id="description" name="description" value="' . htmlspecialchars($costGroup->description) . '">
        </div>
        <div class="form-group">
            <label for="labor_qty">LABOR QTY</label>s
            <input type="number" class="form-control" id="labor_qty" name="labor_qty" value="' . htmlspecialchars($costGroup->labor_qty) . '">
        </div>
        <div class="form-group">
            <label for="labor_unit">LABOR UNIT</label>
            <input type="text" class="form-control" id="labor_unit" name="labor_unit" value="' . htmlspecialchars($costGroup->labor_unit) . '">
        </div>
        <div class="form-group">
            <label for="budget_qty">BUDGET QTY</label>
            <input type="number" class="form-control" id="budget_qty" name="budget_qty" value="' . htmlspecialchars($costGroup->budget_qty) . '">
        </div>
        <div class="form-group">
            <label for="budget_unit">BUDGET UNIT</label>
            <input type="text" class="form-control" id="budget_unit" name="budget_unit" value="' . htmlspecialchars($costGroup->budget_unit) . '">
        </div>
        <div class="form-group">
            <label for="labor_cost">LABOR COST</label>
            <input type="number" class="form-control" id="labor_cost" name="labor_cost" value="' . htmlspecialchars($costGroup->labor_cost) . '">
        </div>
        <div class="form-group">
            <label for="misc_cost">MISC. COST</label>
            <input type="number" class="form-control" id="misc_cost" name="misc_cot" value="' . htmlspecialchars($costGroup->misc_cost) . '">
        </div>
        <div class="form-group">
            <label for="ot_budget">OT BUDGET</label>
            <input type="number" class="form-control" id="ot_budget" name="ot_budget" value="' . htmlspecialchars($costGroup->ot_budget) . '">
        </div>
        <div class="form-group">
            <label for="per_diem_pay">PER DIEM PAY</label>
            <input type="number" class="form-control" id="per_diem_pay" name="per_diem_pay" value="' . htmlspecialchars($costGroup->per_diem_pay) . '">
        </div>
        <div class="form-group">
            <label for="remark">REMARK</label>
            <textarea class="form-control" id="remark" name="remark">' . htmlspecialchars($costGroup->remark) . '</textarea>
        </div>';
        return response()->json(['success' => true, 'html' => $html]);
    } else {
        return response()->json(['success' => false, 'message' => 'Failed to delete cost item.']);
    }
}

public function addNewCost(Request $request, $id)
{
    // Validation can be added here
    $validatedData = $request->validate([
        'existingGroup' => 'required|integer',
        'description' => 'required|string|max:255',
        'labor_qty' => 'nullable|numeric|min:0',
        'labor_unit' => 'nullable|string|max:100',
        'budget_qty' => 'nullable|numeric|min:0',
        'budget_unit' => 'nullable|string|max:100',
        'labor_cost' => 'nullable|numeric|min:0',
        'misc_cost' => 'nullable|numeric|min:0',
        'ot_budget' => 'nullable|numeric|min:0',
        'perdiem_pay' => 'nullable|numeric|min:0',
        'remarks' => 'nullable|string|max:255'
    ]);

    $newCost = new CostModel();
    $newCost->project_cost_group_id = $request->input('existingGroup');
    $newCost->project_id = $id;
    $newCost->project_cost_description = $request->input('description');
    $newCost->project_cost_labor_qty = $request->input('labor_qty');
    $newCost->project_cost_labor_unit = $request->input('labor_unit');
    $newCost->project_cost_budget_qty = $request->input('budget_qty');
    $newCost->project_budget_unit = $request->input('budget_unit');
    $newCost->project_cost_labor_cost = $request->input('labor_cost');
    $newCost->project_cost_misc_cost = $request->input('misc_cost');
    $newCost->project_cost_ot_budget = $request->input('ot_budget');
    $newCost->project_cost_perdiempay = $request->input('perdiem_pay');
    $newCost->project_cost_remaks = $request->input('remarks');
    $newCost->create_date = today();
    $newCost->save();

    return redirect()->back()->with('success', 'New cost added successfully.');
}




public function cost_exportCsv($id)
    {
        $costs = CostModel::where('project_id', $id)->get();

        $response = new StreamedResponse(function () use ($costs) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Add CSV headers
            fputcsv($handle, [
                'Description', 'Labor Qty', 'Labor Unit', 'Budget Qty', 'Budget Unit', 'Labor Cost',
                'Misc. Cost', 'OT Budget', 'Per Diem Pay', 'Subtotal', 'Remark'
            ]);

            // Add CSV rows
            foreach ($costs as $cost) {
                $subtotal = $cost->project_cost_labor_qty * $cost->project_cost_budget_qty *
                    ($cost->project_cost_labor_cost + $cost->project_cost_misc_cost +
                    $cost->project_cost_ot_budget + $cost->project_cost_perdiempay);

                fputcsv($handle, [
                    $cost->project_cost_description, $cost->project_cost_labor_qty, $cost->project_cost_labor_unit,
                    $cost->project_cost_budget_qty, $cost->project_budget_unit, $cost->project_cost_labor_cost,
                    $cost->project_cost_misc_cost, $cost->project_cost_ot_budget, $cost->project_cost_perdiempay,
                    $subtotal, $cost->project_cost_remaks
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="project_budget.csv"');

        return $response;
    }
    static function getCostDetails($id)
    {
        $costDetail = DB::table('project_costs')->join('project_cost_group', 'project_costs.project_cost_group_id','=','project_cost_group.project_cost_group_id')
        ->select('project_costs.*','project_cost_group.*')->where('project_costs.project_cost_id',$id)->first();
        if ($costDetail) {
            return response()->json(['data' => $costDetail]);
        } else {
            return response()->json(['error' => 'Not Found gfff'], 404);
        }
    }
}
