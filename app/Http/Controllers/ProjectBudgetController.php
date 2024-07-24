<?php

namespace App\Http\Controllers;

use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectBudgetController extends Controller
{
    public function getView($id)
    {
        // Không cần khởi tạo đối tượng Submenu nếu không sử dụng nó
        $dataCost = DB::table('project_cost')->where('project_id', $id)->get();
        $dataCostGroup = DB::table('project_cost_group')->get();
        return view('auth.project-budget.budget-detail', [
            'dataCost' => $dataCost,
            'dataCostGroup' => $dataCostGroup,
            'id' => $id
        ]);
    }

    public function showProjects()
    {
        $submenu = Submenu::getData('SELECT * FROM projects'); // Sử dụng phương thức tĩnh
        return view('auth.show-projects', ['submenu' => $submenu]);
    }

    public function showProjectDetail($id)
    {
        $data = DB::table('projects')->where('project_id', $id)->get();
        return view('auth.project-budget.project-budget', ['data' => $data, 'id' => $id]);
    }
}
