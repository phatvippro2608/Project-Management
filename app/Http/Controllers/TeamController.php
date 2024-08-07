<?php

namespace App\Http\Controllers;

use App\Models\TeamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    private $status = [1 => 'Active', 2 => 'Offine', 3 => 'Locked'];

    function getView()
    {
        $team = DB::table('teams')->join('employees', 'employees.employee_id', '=', 'teams.created_by')->get();
        return view('auth.project-employee.team.team', ['team' => $team, 'status' => $this->status]);
    }

    function add(Request $request){
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = $account_id";
        $employee_id = DB::selectOne($sql_get_employee_id)->employee_id;
        $data = [
            'team_name' => $request->team_name,
            'team_description' => $request->team_description,
            'status' => $request->status,
            'created_by' => $employee_id,
        ];
        if($team_id=DB::table('teams')->insertGetId($data)){
            if(DB::table('team_details')->insert(['employee_id'=>$employee_id, 'team_id'=>$team_id, 'team_position_id'=>1])){
                return AccountController::status('Added a new team', 200);
            }else{
                DB::table('team_details')->where('employee_id', $employee_id)->where('team_id', $team_id)->delete();
                DB::table('teams')->where('team_id', $team_id)->delete();
                return AccountController::status('Failed to add a team', 500);
            }
        }else{
            return AccountController::status('Failed to add a team', 500);
        }
    }

    function update(Request $request)
    {
        $id_account = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = $account_id";
        $employee_id = DB::selectOne($sql_get_employee_id)->employee_id;
        $data = [
            'team_name' => $request->team_name,
            'team_description' => $request->team_description,
            'status' => $request->status,
            'created_by' => $employee_id,
        ];
        if(DB::table('team')->where('team_id', $request->team_id)->update($data)){
            return AccountController::status('Updated a team', 200);
        }else{
            return AccountController::status('Failed to update', 500);
        }
    }

    function delete(Request $request){
        if(DB::table('teams')->where('team_id', $request->team_id)->delete()){
            return AccountController::status('Deleted a team', 200);
        }else{
            return AccountController::status('Failed to delete', 500);
        }
    }

    function addEmployee()
    {

    }
    function updateEmployee()
    {

    }

    function deleteEmployee(){

    }
}
