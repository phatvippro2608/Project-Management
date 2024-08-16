<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\ProgressModel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountController;

class ProgressController extends Controller
{
    function getViewHasID($p_id,$id)
    {
        $employees = DB::table('employees')->select('employees.employee_id','employee_code', 'photo', 'last_name', 'first_name')
        ->join('team_details', 'employees.employee_id', '=', 'team_details.employee_id')
        ->join('project_teams', 'team_details.team_id', '=', 'project_teams.team_id')
        ->join('project_locations', 'project_teams.project_id', '=', 'project_locations.project_id')
        ->where('project_locations.project_location_id', $id)
        ->get();
        $leaders = DB::table('employees')->select('employees.employee_id')
        ->join('team_details', 'employees.employee_id', '=', 'team_details.employee_id')
        ->join('project_teams', 'team_details.team_id', '=', 'project_teams.team_id')
        ->join('project_locations', 'project_teams.project_id', '=', 'project_locations.project_id')
        ->where('project_locations.project_location_id', $id)
        ->where('team_details.team_permission', 1)
        ->get();

        return view('auth.progress.progress', compact('employees','leaders', 'p_id', 'id'));
    }
}
