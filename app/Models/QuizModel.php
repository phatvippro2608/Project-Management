<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class QuizModel extends Model
{
    use HasFactory;

    function getInfo()
    {
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);

        return DB::table('employees')
            ->join('accounts','accounts.employee_id','=','employees.employee_id')
            ->join('job_details','employees.employee_id','=','job_details.employee_id')
            ->join('departments','departments.department_id','=','job_details.department_id')
            ->where('accounts.account_id',$account_id)
            ->first();
    }
}
