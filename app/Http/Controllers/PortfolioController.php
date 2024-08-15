<?php

namespace App\Http\Controllers;

use Composer\XdebugHandler\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $employee = DB::table('employees')->where('employee_code', $id)->first();
        if (!$employee) {
            return abort(404);
        }

        $contact = DB::table('contacts')->where('contact_id', $employee->contact_id)->first();
        $account = DB::table('accounts')->where('employee_id', $employee->employee_id)->first();
        $dateOfJoin = DB::table('job_details')->where('employee_id', $employee->employee_id)->oldest('start_date')->first();
        $employee_degree = DB::table('employee_degrees')->where('employee_degrees_id', $employee->employee_degrees_id)->first();

        $recognitions = DB::table('recognitions')->where('employee_id', $employee->employee_id)->get();
        if ($recognitions) {
            foreach ($recognitions as $recognition) {
                $recognition->recognition_type = DB::table('recognition_types')->where('recognition_type_id', $recognition->recognition_type_id)->first();
                $recognition->department = DB::table('departments')->where('department_id', $recognition->department_id)->first();
            }
        }

        $disciplinaries = DB::table('disciplinaries')->where('employee_id', $employee->employee_id)->get();
        if ($disciplinaries) {
            foreach ($disciplinaries as $disciplinarie) {
                $disciplinarie->disciplinarie_type = DB::table('disciplinary_types')->where('disciplinary_type_id', $disciplinarie->disciplinary_type_id)->first();
                $disciplinarie->department = DB::table('departments')->where('department_id', $disciplinarie->department_id)->first();
            }
        }

        // dd($disciplinaries);

        $job_detail = DB::table('job_details')->where('employee_id', $employee->employee_id)->first();
        $job_title = null;
        $job_team = null;
        $department = null;

        if ($job_detail) {
            $job_title = DB::table('job_titles')->where('job_title_id', $job_detail->job_title_id)->first();
            $job_team = DB::table('job_teams')->where('team_id', $job_detail->job_team_id)->first();
            $department = DB::table('departments')->where('department_id', $job_detail->department_id)->first();
        }

        $projects = DB::table('employees')
            ->join('team_details', 'team_details.employee_id', '=', 'employees.employee_id')
            ->join('project_teams', 'project_teams.team_id', '=', 'team_details.team_id')
            ->join('projects', 'projects.project_id', '=', 'project_teams.project_id')
            ->join('phases', 'phases.phase_id', '=', 'projects.phase_id')
            ->where('employees.employee_code', $id)
            ->select('projects.*', 'phases.phase_name_eng')
            ->get();

        $status = 2;

        $inProject = DB::table('employees')
            ->join('team_details', 'team_details.employee_id', '=', 'employees.employee_id')
            ->join('project_teams', 'project_teams.team_id', '=', 'team_details.team_id')
            ->join('projects', 'projects.project_id', '=', 'project_teams.project_id')
            ->where('employees.employee_code', $id)
            ->select('projects.project_date_start', 'projects.project_date_end')
            ->get();

        $currentTime = Carbon::now()->format('Y-m-d');
        foreach ($inProject as $key => $value) {
            $projectStartDate = Carbon::parse($value->project_date_start)->format('Y-m-d');
            $projectEndDate = Carbon::parse($value->project_date_end)->format('Y-m-d');
            if ($currentTime >= $projectStartDate && $currentTime <= $projectEndDate) {
                $status = 1;
                break;
            }
        }

        $inLeave = DB::table('employees')
            ->join('leave_applications', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->where('employees.employee_code', $id)
            ->get();

        foreach ($inLeave as $key => $value) {
            $projectStartDate = Carbon::parse($value->start_date)->format('Y-m-d');
            $projectEndDate = Carbon::parse($value->end_date)->format('Y-m-d');
            if ($value->leave_status == "approve" && $currentTime >= $projectStartDate && $currentTime <= $projectEndDate) {
                $status = 3;
                break;
            }
        }

        $inQuit = DB::table('employees')
            ->join('contacts', 'contacts.contact_id', '=', 'employees.contact_id')
            ->where('fired', 'true')
            ->where('employees.employee_code', $id)
            ->first();
        if ($inQuit) {
            $status = 4;
        }
        
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
