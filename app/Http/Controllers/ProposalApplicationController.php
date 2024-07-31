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

        $sql_get_employee_id = "SELECT * FROM employees, account WHERE employees.employee_id = account.employee_id AND id_account = $account_id";
        $employee = DB::selectOne($sql_get_employee_id);
        $employee_id = $employee->employee_id;

        $list_department = [];
        if($permission == 0){
            $list_department = DB::table('proposal_application')->join('employees', 'employees.employee_id','=','proposal_application.employee_id')->where('employees.employee_id', $employee_id)->get();
        }else if($permission == 3){
            $department_id = DB::table('employees')
                ->join('job_detail', 'job_detail.employee_id','=','employees.employee_id')
                ->join('departments', 'job_detail.id_department','=','departments.department_id')->where('employees.employee_id',$employee_id)->first()->id_department;
            $list_department = DB::table('proposal_application')
                ->join('employees', 'employees.employee_id','=','proposal_application.employee_id')
                ->join('job_detail', 'job_detail.employee_id','=','employees.employee_id')
                ->join('departments', 'job_detail.id_department','=','departments.department_id')->where('departments.department_id',$department_id)->get();
        }else if($permission == 4){
            $list_department = DB::table('proposal_application')->join('employees', 'employees.employee_id','=','proposal_application.employee_id')->get();
        }

        return view('auth.proposal.proposal-application',
            ['list_department' => $list_department]);
    }
}
