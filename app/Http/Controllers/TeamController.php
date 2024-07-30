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
        $team = DB::table('team')->join('employees', 'employees.id_employee', '=', 'team.created_by')->get();
        return view('auth.project-employee.team.team', ['team' => $team, 'status' => $this->status]);
    }

    function add(Request $request){
        $id_account = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $sql_get_id_employee = "SELECT * FROM employees, account WHERE employees.id_employee = account.id_employee AND id_account = $id_account";
        $id_employee = DB::selectOne($sql_get_id_employee)->id_employee;
        $data = [
            'team_name' => $request->team_name,
            'team_description' => $request->team_description,
            'status' => $request->status,
            'created_by' => $id_employee,
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
        $sql_get_id_employee = "SELECT * FROM employees, account WHERE employees.id_employee = account.id_employee AND id_account = $id_account";
        $id_employee = DB::selectOne($sql_get_id_employee)->id_employee;
        $data = [
            'team_name' => $request->team_name,
            'team_description' => $request->team_description,
            'status' => $request->status,
            'created_by' => $id_employee,
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
