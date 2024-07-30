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
        DB::table('team')->insert($data);
    }

    function update(Request $request)
    {
        $data = [
            'team_name' => $request->team_name,
            'team_description' => $request->team_description,
            'status' => $request->status,
            'created_by' => $request->created_by,
            'created_at' => $request->created_at,
            'updated_at' => $request->updated_at
        ];
        DB::table('team')->where('id_team', $request->id_team,)->insert($data);
    }

    function delete(Request $request){

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
