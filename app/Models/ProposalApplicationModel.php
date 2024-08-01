<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Facades\Request;

class ProposalApplicationModel extends Model
{
    use HasFactory;
    public function getListProposal()
    {
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $permission = DB::table('accounts')
            ->where('accounts.account_id', $account_id)
            ->pluck('permission')
            ->first(); // Lấy giá trị đầu tiên (giả sử luôn có một hàng được trả về)

        $employee_id = DB::table('employees')
            ->join('accounts', 'accounts.employee_id','=','employees.employee_id')
            ->where('accounts.account_id', $account_id)
            ->pluck('employees.employee_id')
            ->first(); // Lấy giá trị đầu tiên (giả sử luôn có một hàng được trả về)

        $list_proposal = [];
        if($permission == 0){
            $list_proposal = DB::table('proposal_application')->join('employees', 'employees.employee_id','=','proposal_application.id_employee')->where('employees.employee_id', $employee_id)->get();
        }else if($permission == 3){
            $department_id = DB::table('employees')
                ->join('job_detail', 'job_detail.employee_id','=','employees.employee_id')
                ->join('departments', 'job_detail.id_department','=','departments.department_id')->where('employees.employee_id',$employee_id)->first()->id_department;
            $list_proposal = DB::table('proposal_application')
                ->join('employees', 'employees.employee_id','=','proposal_application.id_employee')
                ->join('job_detail', 'job_detail.employee_id','=','employees.employee_id')
                ->join('departments', 'job_detail.id_department','=','departments.department_id')->where('departments.department_id',$department_id)->get();
        }else if($permission == 4){
            $list_proposal = DB::table('proposal_application')->join('employees', 'employees.employee_id','=','proposal_application.id_employee')->get();
        }

        return $list_proposal;
    }
}
