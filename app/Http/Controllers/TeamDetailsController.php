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
            ->join('team_position', 'team_details.team_position_id', '=', 'team_position.team_position_id')
            ->where("team_id", $team_id)->get();

//        dd($employees);
        $sql = "SELECT
                    employees.*,
                    CASE
                        WHEN team_details.employee_id IS NOT NULL THEN 1
                        ELSE 0
                    END AS isAtTeam
                FROM
                    employees
                LEFT JOIN
                    accounts ON accounts.employee_id = employees.employee_id
                LEFT JOIN
                    team_details ON team_details.employee_id = employees.employee_id AND team_details.team_id = $team_id
                ";
        $all_employees = DB::select($sql);
//        dd($all_employees);
        $teams = DB::table("teams")->where("team_id", $team_id)->get();
//        dd($id_team);
        return view('auth.project-employee.team-details.team-details', [
            'employees' => $employees,
            'all_employees' => $all_employees,
            'teams' => $teams,
            'status' => $this->status,
        ]);
    }

    function add()
    {

    }

    function update()
    {

    }

    function delete()
    {

    }

}
