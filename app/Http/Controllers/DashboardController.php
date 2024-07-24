<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\ProjectModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getViewDashboard()
    {
        $em_c = count(EmployeeModel::all());
        $team_c = 5;
        $project_c = count(ProjectModel::all());
//        $task_c = Task
        return view('auth.dashboard.dashboard',[
            'em_c'=>$em_c
        ]);
    }


}
