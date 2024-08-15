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
            $dataCostGroup = DB::table('project_cost_group')->get();
        }
            else{
                $dataCost = DB::table('project_costs')
                ->join('project_cost_group', 'project_costs.project_cost_group_id', '=', 'project_cost_group.project_cost_group_id')
                ->join('project_locations', 'project_cost_group.project_location_id', '=', 'project_locations.project_location_id') // Corrected join
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
    
            // Fetch project name
            $project = DB::table('projects')->select('project_name')
                ->where('project_id', $id)
                ->first();
    
            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found',
                ]);
            }
    
            $prjNameCurrent = $project->project_name;
    
            // Fetch project location
            $prjLocationName = $dataExcel['data'][2][1];
            $prjLocation = DB::table('project_locations')->select('project_location_id')
                ->where('project_id', $id)
                ->where('project_location_name', $prjLocationName)
                ->first();
    
            if (!$prjLocation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project location not found',
                ]);
            }
    
            $prjLocationId = $prjLocation->project_location_id;
    
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
                    // Log values for debugging
                    Log::info('Description: ' . $description);
                    Log::info('Labor QTY: ' . $laborQTY);
                    Log::info('Labor Unit: ' . $laborUnit);
                    Log::info('Budget QTY: ' . $budgetQTY);
                    Log::info('Budget Unit: ' . $budgetUnit);
                    Log::info('Labor Cost: ' . $laborCost);
                    Log::info('Misc Cost: ' . $miscCost);
                    Log::info('OT Budget: ' . $otBudget);
                    Log::info('Per Diem Pay: ' . $perDiemPay);
                    Log::info('Remark: ' . $remark);
    
                    // Check if all required fields are not empty
                    if (!empty($description) || !empty($laborQTY) || !empty($laborUnit) ||
                        !empty($budgetQTY) || !empty($budgetUnit) || !empty($laborCost) ||
                        !empty($miscCost) || !empty($otBudget) || !empty($perDiemPay) || !empty($remark)) {
    
                        // Insert project costs
                        DB::table('project_costs')->insert([
                            'project_id' => $id,
                            'project_cost_description' => $description,
                            'project_cost_labor_qty' => $laborQTY,
                            'project_cost_labor_unit' => $laborUnit,
                            'project_cost_budget_qty' => $budgetQTY,
                            'project_budget_unit' => $budgetUnit,
                            'project_cost_labor_cost' => $laborCost,
                            'project_cost_misc_cost' => $miscCost,
                            'project_cost_perdiempay' => $perDiemPay,
                            'project_cost_ot_budget' => $otBudget,
                            'project_cost_remaks' => $remark,
                            'project_cost_group_id' => $group_id,
                            'create_date' => today()
                        ]);
                    }
    
                    // Skip to the next row
                    $num_row++;
                    continue;
                }
    
                $num_row++;
            }
    
            // Check if import was successful
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
        $dataLoca = DB::table('project_locations')
        ->join('projects', 'projects.project_id','=','project_locations.project_id')
        ->select('projects.*', 'project_locations.*')->where('project_locations.project_id', $id)->where('project_locations.project_location_id', $keyword)->first();
        
    }else{
        $dataLoca = DB::table('project_locations')
        ->join('projects', 'projects.project_id','=','project_locations.project_id')
        ->select('projects.*', 'project_locations.*')->where('project_locations.project_id', $id)->first();
    }

    
    $locations = DB::table('project_locations')
    ->join('projects', 'projects.project_id','=','project_locations.project_id')
    ->select('projects.*', 'project_locations.*')->where('project_locations.project_id', $id)->get();
    foreach ($items as $item) {
        $subtotal2 = $item->project_cost_labor_qty *
                    $item->project_cost_budget_qty *
                    ($item->project_cost_labor_cost +
                        $item->project_cost_misc_cost +
                        $item->project_cost_ot_budget +
                        $item->project_cost_perdiempay);
        $subtotal1 += $subtotal2;
    }
    $total += $subtotal1;

    return view('auth.project-budget.project-budget', [
        'data' => $data,
        'id' => $id,
        'total' => $total,
        'contract' => $contract,
        'contactEmployee' => $contactEmployee,
        'customer' => $customer,
        'locations' => $locations,
        'dataLoca' => $dataLoca,
        'keyword' => $keyword
    ]);
}
    public function viewCost($id, $group_id){
        
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
    public function renameCostGroup(Request $request)
    {
        $costGroupId = $request->input('costGroupId');
        $costGroupName = $request->input('costGroupName');

        // Update logic here
        $costGroup = CostGroupModel::find($costGroupId);
        $costGroup->project_cost_group_name = $costGroupName;
        $costGroup->save();

        return redirect()->back()->with('success', 'Cost group name updated successfully.');
    }


    public function addNewGroupCost(Request $request, $id)
{
    $groupSelect = $request->input('groupSelect');

    $newCost = new CostModel();
    $newCost->project_cost_group_id = $groupSelect;
    $newCost->project_id = $id;
    // Assign other fields for the cost here
    $newCost->save();

    return redirect()->back()->with('success', 'New group cost added successfully.');
}


public function createCostGroup(Request $request, $id)
{
    $request->validate([
        'newGroupName' => 'required|string|max:255',
    ]);

    $newGroup = new CostGroupModel();
    $newGroup->project_cost_group_name = $request->input('newGroupName');
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
    $newCost->save();

    return redirect()->back()->with('success', 'New cost added successfully.');
}
public function deleteCostGroup(Request $request, $project_id, $cost_group_id)
{
    $costGroup = CostGroupModel::find($cost_group_id);

    if ($costGroup) {
        // Optional: Check if the cost group is used in other places and handle it accordingly
        $costGroup->delete();

        return response()->json(['success' => true]);
    } else {
        return response()->json(['success' => false, 'message' => 'Cost group not found.']);
    }
}
    public function getViewCommission($id){
        $dataCommission = DB::table('project_cost_commission')->where('project_id', $id)->get();
        $dataGroupCommission = DB::table('project_group_cost_commission')->get();
        $project = DB::table('projects')->where('project_id', $id)->first();
        return view('auth.project-budget.budget-commission', [
            'dataCommission' => $dataCommission,
            'dataGroupCommission' => $dataGroupCommission,
            'id' => $id,
            'project'=>$project
        ]);
    }

    public function getCommissionDetails(Request $request, $id)
{
    $groupId = $request->input('group_id');

    // Fetch commission details based on groupId and projectId
    $commissionDetails = DB::table('project_cost_commission')->where('project_id', $id)
                                    ->where('groupcommission_id', $groupId)
                                    ->get();

    if ($commissionDetails) {
        return response()->json([
            'success' => true,
            'data' => $commissionDetails
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'No details found.'
        ]);
    }
}
public function exportCsv($id)
    {
        $project = DB::table('projects')->where('project_id', $id)->first();
        $dataGroupCommission = DB::table('project_group_cost_commission')->get();
        $dataCommission = DB::table('project_cost_commission')->where('project_id', $id)->get();

        $filename = "project_budget_{$id}.csv";
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID', 'Group Name', 'Description', 'Amount']);

        foreach ($dataGroupCommission as $commissionGroup) {
            foreach ($dataCommission as $data) {
                if ($data->groupcommission_id == $commissionGroup->group_id) {
                    fputcsv($handle, [
                        $commissionGroup->group_id,
                        $commissionGroup->groupcommission_name,
                        $data->description,
                        $data->amount
                    ]);
                }
            }
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function deleteCostCommission($project_id, $cost_commission_id)
    {
        try {
            DB::table('project_cost_commission')->where('commission_id', $cost_commission_id)->delete();
            return response()->json(['success' => true, 'message' => 'Cost commission item deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete cost commission item.']);
        }
    }
    public function updateCommission(Request $request, $project_id, $commission_id)
{
    $costCommission = CostComissionModel::find($commission_id);

    if (!$costCommission) {
        return response()->json([
            'success' => false,
            'message' => 'Commission not found.'
        ], 404);
    }

    $costCommission->description = $request->input('description');
    $costCommission->amount = $request->input('amount');
    $costCommission->save();

    return response()->json([
        'success' => true,
        'message' => 'Cost commission updated successfully.'
    ]);
}
public function addNewCommission(Request $request, $project_id)
{
    try {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'group_id' => 'required|integer',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        // Create new commission entry
        $CommissionCost = new CostComissionModel();
        $CommissionCost->project_id = $project_id;
        $CommissionCost->groupcommission_id = $request->input('group_id');
        $CommissionCost->description = $request->input('description');
        $CommissionCost->amount = $request->input('amount');

        // Save and check if successful
        if ($CommissionCost->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add commission.'
            ]);
        }
    } catch (\Exception $e) {
        Log::error('Error adding commission: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Server error. Please try again later.'
        ]);
    }
}
public function editNameGroup(Request $request, $project_id, $group_id) {
    $request->validate([
        'groupName' => 'required|string|max:255',
    ]);

    $groupName = $request->input('groupName');

    DB::table('project_group_cost_commission')
        ->where('group_id', $group_id)
        ->update(['groupcommission_name' => $groupName]);

    return response()->json(['success' => true, 'message' => 'Cost group name updated successfully.']);
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
    public function addNewCommissionGroup(Request $request, $project_id)
{
    // Validate the request data
    $request->validate([
        'groupcommission_name' => 'required|string|max:255',
    ]);

    // Insert the new group into the database and get the inserted ID
    $insertedId = DB::table('project_group_cost_commission')->insertGetId([
        'groupcommission_name' => $request->input('groupcommission_name'),
    ]);

    // Check if the insertion was successful
    if ($insertedId) {
        // Return a success response with the new group ID
        return response()->json([
            'success' => true,
            'message' => 'Group added successfully!',
            'group_id' => $insertedId // Return the new group ID
        ]);
    } else {
        // Return an error response if the insertion failed
        return response()->json([
            'success' => false,
            'message' => 'Failed to add group.'
        ]);
    }
}

}

