<?php

namespace App\Http\Controllers;

use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data = DB::table('projects')->where('project_id', $id)->get();
        return view('auth.project-budget.project-budget', ['data' => $data, 'id' => $id]);
    }
    public function editBudget($id){
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
    public function getBudgetData($id, $costGroupId)
{
    // Kiểm tra tham số
    if (!is_numeric($id) || !is_numeric($costGroupId)) {
        return response()->json(['error' => 'Tham số không hợp lệ'], 400);
    }

    try {
        // Lấy dữ liệu ngân sách
        $budgetData = DB::table('project_cost')->where('project_cost_group_id', $costGroupId)->get();
        return response()->json($budgetData);
    } catch (\Exception $e) {
        // Xử lý lỗi và ghi nhật ký
        \Log::error($e->getMessage());
        return response()->json(['error' => 'Đã xảy ra lỗi khi xử lý yêu cầu'], 500);
    }
}



    public function addNewGroup($group_name){
        DB::table('project_cost_group')->insert(['project_cost_group_name' => $group_name]);
        return redirect()->back()->with('success', 'New group added successfully!');
    }
    
}
