<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\ProjectModel;
use App\Models\Submenu;
use App\Models\TaskModel;
use App\Models\TeamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getViewDashboard()
    {
        $em_c = count(EmployeeModel::all());
        $team_c = count(TeamModel::all());
        $project_c = count(ProjectModel::all());

        $task_c = DB::table('tasks')->count();
        $sub_c = DB::table('sub_tasks')->count();

        $sql = "SELECT projects.project_name, TIME(recent_project.created_at) as created_at FROM account, recent_project, projects WHERE account.id_account = recent_project.id_account AND recent_project.project_id = projects.project_id AND DATE(recent_project.created_at) = CURDATE()  LIMIT 5";
        $recent_project = DB::select($sql);

        return view('auth.dashboard.dashboard',[
            'em_c'=>$em_c,
            'team_c'=>$team_c,
            'project_c'=>$project_c,
            'task_c'=>$task_c,
            'sub_c'=>$sub_c,
            'recent_project'=>$recent_project,
        ]);
    }
}
