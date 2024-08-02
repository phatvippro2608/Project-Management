<?php

namespace App\Http\Controllers;

use Composer\XdebugHandler\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    public function getView()
    {
        $sql = DB::table('employees')->get()->map(function ($item) {
            // Kiểm tra xem photo có tồn tại và không phải là null
            $item->photoExists = !is_null($item->photo) && file_exists(public_path($item->photo));
            return $item;
        });
        return view('auth.portfolio.portfolio', ['sql' => $sql]);
    }
    public function getViewHasId($id)
    {
        $existsCode = DB::table('employees')->where('employee_code', $id)->exists();
        if (!$existsCode) {
            return abort(404); // Trả về trang 404 nếu không tìm thấy mã nhân viên
        }
        $employee = DB::table('employees')->where('employee_code', $id)->first();
        $contact = DB::table('contacts')->where('contact_id', $employee->contact_id)->first();
        $account = DB::table('accounts')->where('employee_id', $employee->employee_id)->first();
        $dateOfJoin = DB::table('job_details')->where('employee_id', $employee->employee_id)->oldest('start_date')->first();
        $employee_degree = DB::table('employee_degrees')->where('employee_degrees_id', $employee->employee_degrees_id)->first();

        $recognitions = DB::table('recognitions')->where('employee_id', $employee->employee_id)->get();
        foreach ($recognitions as $recognition) {
            $recognition->recognition_type = DB::table('recognition_types')->where('recognition_type_id', $recognition->recognition_type_id)->first();
            $recognition->department = DB::table('departments')->where('department_id', $recognition->department_id)->first();
        }

        $job_detail = DB::table('job_details')->where('employee_id', $employee->employee_id)->first();
        $job_title = null;
        $job_team = null;
        $department = null;

        if ($job_detail) {
            $job_title = DB::table('job_titles')->where('job_title_id', $job_detail->job_title_id)->first();
            $job_team = DB::table('job_teams')->where('team_id', $job_detail->job_team_id)->first();
            $department = DB::table('departments')->where('department_id', $job_detail->department_id)->first();
        }
        $status = 1;
        return view(
            'auth.portfolio.portfolioHasId',
            [
                'employee' => $employee,
                'dateOfJoin' => $dateOfJoin,
                'contact' => $contact,
                'account' => $account,
                'recognitions' => $recognitions,
                'job_team' => $job_team,
                'job_title' => $job_title,
                'department' => $department,
                'employee_degree' => $employee_degree,
                'status' => $status
            ]
        );
    }


}
