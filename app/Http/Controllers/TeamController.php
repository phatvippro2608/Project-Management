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
        $team = DB::table('team')->join('employees', 'employees.employee_id', '=', 'team.created_by')->get();
        return view('auth.project-employee.team.team', ['team' => $team, 'status' => $this->status]);
    }

    function add(Request $request){
        $id_account = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $sql_get_employee_id = "SELECT * FROM employees, account WHERE employees.employee_id = account.employee_id AND id_account = $id_account";
        $employee_id = DB::selectOne($sql_get_employee_id)->employee_id;
        $data = [
            'team_name' => $request->team_name,
            'team_description' => $request->team_description,
            'status' => $request->status,
            'created_by' => $employee_id,
        ];
        if(DB::table('team')->insert($data)){
            return AccountController::status('Thêm thành công', 200);
        }else{
            return AccountController::status('Thêm thất bại', 500);
        }
    }

    function update(Request $request)
    {
        $id_account = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $sql_get_employee_id = "SELECT * FROM employees, account WHERE employees.employee_id = account.employee_id AND id_account = $id_account";
        $employee_id = DB::selectOne($sql_get_employee_id)->employee_id;
        $data = [
            'team_name' => $request->team_name,
            'team_description' => $request->team_description,
            'status' => $request->status,
            'created_by' => $employee_id,
        ];
        if(DB::table('team')->where('id_team', $request->id_team)->update($data)){
            return AccountController::status('Cập nhật thành công', 200);
        }else{
            return AccountController::status('Cập nhật thất bại', 500);
        }
    }

    function delete(Request $request){
        if(DB::table('team')->where('id_team', $request->id_team)->delete()){
            return AccountController::status('Xóa thành công', 200);
        }else{
            return AccountController::status('Xóa thất bại', 500);
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
