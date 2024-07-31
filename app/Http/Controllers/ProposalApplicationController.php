<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProposalApplicationController extends Controller
{
    public function getView()
    {
        $permission = AccountController::permission();
        //permis = 0 -> employee (uesr)
        //permis = 3 -> Direct department (trưởng phòng ban)
        //permis = 4 -> Direct Manager (Quản lý)
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);

        $sql_get_id_employee = "SELECT * FROM employees, account WHERE employees.id_employee = account.id_employee AND id_account = $account_id";
        $employee = DB::selectOne($sql_get_id_employee);
        $employee_id = $employee->id_employee;

        $list_department = [];
        if($permission == 0){
            $list_department = DB::table('proposal_application')->join('employees', 'employees.id_employee','=','proposal_application.id_employee')->where('employees.id_employee', $employee_id)->get();
        }else if($permission == 3){
            $department_id = DB::table('employees')
                ->join('job_detail', 'job_detail.id_employee','=','employees.id_employee')
                ->join('departments', 'job_detail.id_department','=','departments.department_id')->where('employees.id_employee',$employee_id)->first()->id_department;
            $list_department = DB::table('proposal_application')
                ->join('employees', 'employees.id_employee','=','proposal_application.id_employee')
                ->join('job_detail', 'job_detail.id_employee','=','employees.id_employee')
                ->join('departments', 'job_detail.id_department','=','departments.department_id')->where('departments.department_id',$department_id)->get();
        }else if($permission == 4){
            $list_department = DB::table('proposal_application')->join('employees', 'employees.id_employee','=','proposal_application.id_employee')->get();
        }

        return view('auth.proposal.proposal-application',
            ['list_department' => $list_department]);
    }
}
