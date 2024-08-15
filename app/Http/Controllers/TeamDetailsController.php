<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamDetailsController extends Controller
{
    private $status = [1 => 'Active', 2 => 'Offine', 3 => 'Locked'];
    function getView(Request $request)
    {
        $team_id = $request->team_id;
        $employees = DB::table("employees")
            ->join('accounts', 'employees.employee_id', '=', 'accounts.employee_id')
            ->join('team_details', 'employees.employee_id', '=', 'team_details.employee_id')
            ->leftjoin('team_positions', 'team_details.team_permission', '=', 'team_positions.team_permission')
            ->where("team_id", $team_id)->get();

//        dd($employees);
        $sql = "SELECT
                    employees.*, accounts.*,team_details.team_permission as team_permission,
                    CASE
                        WHEN team_details.employee_id IS NOT NULL THEN 1
                        ELSE 0
                    END AS isAtTeam
                FROM
                    employees
                JOIN
                    accounts ON accounts.employee_id = employees.employee_id
                LEFT JOIN
                    team_details ON team_details.employee_id = employees.employee_id AND team_details.team_id = $team_id
                ";
        $all_employees = DB::select($sql);
//        dd($all_employees);
        $teams = DB::table("teams")->where("team_id", $team_id)->get();
        $team_positions = DB::table("team_positions")->get();
        return view('auth.project-employee.team-details.team-details', [
            'employees' => $employees,
            'all_employees' => $all_employees,
            'teams' => $teams[0],
            'team_positions' => $team_positions,
            'status' => $this->status,
        ]);
    }

    function update(Request $request)
    {
        $employee_id = $request->employee_id;
        $team_id = $request->team_id;
        $team_permission = $request->input('team_permission', null);
        $checked = $request->checked;
        if($checked==1){
            if(DB::table('team_details')->insert(['employee_id'=>$employee_id, 'team_id'=>$team_id, 'team_permission'=>$team_permission])){
                return AccountController::status('Successfully Updated',200);
            }else{
                return AccountController::status('Fail Updated', 500);
            }
        }
        else {
            if(DB::table('team_details')->where('employee_id', $employee_id)->where('team_id', $team_id)->delete()){
                return AccountController::status('Successfully Updated',200);
            }else{
                return AccountController::status('Fail Updated', 500);
            }
        }
    }

    function updatePosition(Request $request)
    {
        $employee_id = $request->employee_id;
        $team_id = $request->team_id;
        $team_permission = $request->input('team_permission', null);
        if(DB::table('team_details')->where('employee_id', $employee_id)->where('team_id', $team_id)->exists()){
            if(DB::table('team_details')->where('employee_id', $employee_id)->where('team_id', $team_id)->update(['team_permission'=>$team_permission])){
                return AccountController::status('Successfully Updated',200);
            }else{
                return AccountController::status('Fail Updated', 500);
            }
        }
    }

}
