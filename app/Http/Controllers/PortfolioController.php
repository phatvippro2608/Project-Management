<?php

namespace App\Http\Controllers;

use Composer\XdebugHandler\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    public function getView()
    {
        $sql = DB::table('employees')
            ->leftJoin('job_details', 'employees.employee_id', '=', 'job_details.employee_id')
            ->leftJoin('departments', 'job_details.department_id', '=', 'departments.department_id')
            ->select('employees.*', 'job_details.department_id', 'departments.department_name')
            ->get()
            ->map(function ($item) {
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

        $disciplinaries = DB::table('disciplinaries')->where('employee_id', $employee->employee_id)->get();
        foreach ($disciplinaries as $disciplinarie) {
            $disciplinarie->disciplinarie_type = DB::table('disciplinarie_types')->where('disciplinarie_type_id', $disciplinarie->disciplinarie_type_id)->first();
            $disciplinarie->department = DB::table('departments')->where('department_id', $disciplinarie->department_id)->first();
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

        // $job_detail = DB::table('job_details')
        //     ->leftJoin('job_titles', 'job_details.job_title_id', '=', 'job_titles.job_title_id')
        //     ->leftJoin('job_teams', 'job_details.job_team_id', '=', 'job_teams.team_id')
        //     ->leftJoin('departments', 'job_details.department_id', '=', 'departments.department_id')
        //     ->select(
        //         'job_details.*',
        //         'job_titles.title_name', // Điều chỉnh tên cột theo tên thực tế trong bảng job_titles
        //         'job_teams.team_name',   // Điều chỉnh tên cột theo tên thực tế trong bảng job_teams
        //         'departments.department_name' // Điều chỉnh tên cột theo tên thực tế trong bảng departments
        //     )
        //     ->where('job_details.employee_id', $employee->employee_id)
        //     ->first();
            
        $status = 1;

        // $team_detail = DB::table('team_detail')->where('employee_id', $employee->employee_id)->get();

        $projects = DB::table('projects')
            ->join('phases', 'projects.phase_id', '=', 'phases.phase_id')
            ->join('team_detail', 'projects.employee_id', '=', 'team_detail.employee_id')
            ->join('teams', 'team_detail.team_id', '=', 'teams.team_id')
            ->join('employees', 'team_detail.employee_id', '=', 'employees.employee_id')
            ->select('projects.*', 'phases.phase_name_eng')
            ->where('employees.employee_code', $id)
            ->get();
        // return dd($projects);
        return view(
            'auth.portfolio.portfolioHasId',
            [
                'employee' => $employee,
                'dateOfJoin' => $dateOfJoin,
                'contact' => $contact,
                'account' => $account,
                'recognitions' => $recognitions,
                'disciplinaries' => $disciplinaries,
                'job_team' => $job_team,
                'job_title' => $job_title,
                'department' => $department,
                'employee_degree' => $employee_degree,
                'projects' => $projects,
                'status' => $status
            ]
        );
    }


}
