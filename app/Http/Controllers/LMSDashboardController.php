<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeeModel;
use App\StaticString;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class LMSDashboardController extends Controller
{
    public function getView(Request $request) {
        $account_id = $request->session()->get(\App\StaticString::ACCOUNT_ID);
        $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = $account_id";
        $employee = DB::selectOne($sql_get_employee_id);
        $course_c = DB::table('courses')->count();
        $employee_id = $employee->employee_id;
        $data = DB::table('courses_employees')
            ->join('employees', 'courses_employees.employee_id', '=', 'employees.employee_id')
            ->join('courses', 'courses_employees.course_id', '=', 'courses.course_id')
            ->select('employees.first_name', 'employees.last_name', 'courses.course_id', 'courses.course_name','courses_employees.start_date', 'courses_employees.end_date', 'courses_employees.progress')
            ->where('courses_employees.employee_id', $employee_id)
            ->get();
        $count = $data->count();
        return view('auth.lms.lms-dashboard', ['employee' => $employee, 'data' => $data, 'count'=>$count, 'course_c' => $course_c]);
    }
    
}
