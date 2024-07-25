<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\ProjectModel;
use App\Models\Submenu;
use App\Models\TaskModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getViewDashboard()
    {
        $em_c = count(EmployeeModel::all());
        $team_c = 5;
        $project_c = count(ProjectModel::all());

        $task_sql = "SELECT COUNT(*) FROM tasks";
        $task_c = DB::select($task_sql); //TaskModel::all()->count();
        $sub_c = 0; //Submenu::all()->count();
        return view('auth.dashboard.dashboard',[
            'em_c'=>$em_c,
            'team_c'=>$team_c,
            'project_c'=>$project_c,
            'task_c'=>$task_c,
            'sub_c'=>$sub_c
        ]);
    }


}
