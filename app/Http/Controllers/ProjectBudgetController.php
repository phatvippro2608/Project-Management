<?php

namespace App\Http\Controllers;

use App\Models\CostModel;
use App\Models\CostGroupModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ProjectBudgetController extends Controller
{


    public function getView($id)
    {

        $dataCost = DB::table('project_cost')->where('project_id', $id)->get();
        $dataCostGroup = DB::table('project_cost_group')->get();
        $contingency_price = DB::table('projects')->where('project_id', $id)->first();
        $dataCostGroupData = DB::table('project_cost_datagroup')->get();


        return view('auth.project-budget.budget-detail', [
            'dataCost' => $dataCost,
            'dataCostGroup' => $dataCostGroup,
            'dataCostGroupData' => $dataCostGroupData,
            'id' => $id,
            'contingency_price' => $contingency_price
        ]);
    }

    public function showProjects()
    {

        $submenu = DB::table('projects')->get();
        return view('auth.show-projects', ['submenu' => $submenu]);
    }

    public function showProjectDetail($id)
    {
        $data = DB::table('projects')->where('project_id', $id)->first();
        $total = 0;
        $subtotal1=0;
        $items=DB::table('project_cost')->where('project_id', $id)->get();
        foreach($items as $item){
            $subtotal2=$item->project_cost_labor_qty *
            $item->project_cost_budget_qty *
            ($item->project_cost_labor_cost +
                $item->project_cost_misc_cost +
                $item->project_cost_ot_budget +
                $item->project_cost_perdiempay);
            $subtotal1+=$subtotal2;
        }
        $total+= $subtotal1;
        return view('auth.project-budget.project-budget', ['data' => $data, 'id' => $id, 'total'=>$total]);
    }

    public function editBudget($id)
    {
        $dataCost = DB::table('project_cost')->where('project_id', $id)->get();
        $dataCostGroup = DB::table('project_cost_group')->get();
        $contingency_price = DB::table('projects')->where('project_id', $id)->first();
        $dataCostGroupData = DB::table('project_cost_datagroup')->get();
        return view('auth.project-budget.budget-edit', [
            'dataCost' => $dataCost,
            'dataCostGroup' => $dataCostGroup,
            'dataCostGroupData' => $dataCostGroupData,
            'id' => $id,
            'contingency_price' => $contingency_price
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
            $budgetData = DB::table('project_cost')
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
        DB::table('project_cost')
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
            DB::table('project_cost')->where('project_cost_id', $cost_id)->delete();
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
            <label for="labor_qty">LABOR QTY</label>
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
            <input type="number" class="form-control" id="misc_cost" name="misc_cost" value="' . htmlspecialchars($costGroup->misc_cost) . '">
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
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
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
}
