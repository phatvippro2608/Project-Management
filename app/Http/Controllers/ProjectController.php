<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function getView()
    {
        $projects = DB::table('projects')
            ->join('contracts', 'contracts.contract_id', '=', 'projects.contract_id')
            ->join('customers', 'customers.customer_id', '=', 'contracts.customer_id')
            ->join('phases', 'phases.phase_id', '=', 'projects.phase_id')
            ->join('project_teams', 'project_teams.project_id', '=', 'projects.project_id')
            ->select(
                'projects.*',
                'project_teams.*',
                DB::raw("CONCAT(customers.company_name, ' - ', customers.last_name, ' ', customers.first_name) AS customer_info"),
                'phases.phase_name_eng'
            )
            ->orderBy('project_teams.project_id', 'asc')
            ->get();

        foreach ($projects as $project) {
            $project->team_members = DB::table('team_details')
                ->join('employees', 'employees.employee_id', '=', 'team_details.employee_id')
                ->join('team_positions', 'team_positions.team_position_id', '=', 'team_details.team_position_id')
                ->where('team_id', $project->team_id)
                ->orderBy('team_permission', 'asc')->get();
        }
        $teams = DB::table('teams')->get();
        $contracts = DB::table('contracts')
            ->leftjoin('projects', 'contracts.contract_id', '=', 'projects.contract_id')
            ->whereNull('projects.contract_id')->select('contracts.contract_id', 'contracts.contract_name')->get();
        return view('auth.projects.project-list', compact('projects', 'teams', 'contracts'));
    }


    public function InsPJ(Request $request)
    {
        $rules = [
            'project_name' => 'required|string|max:255|min:5',
            'project_description' => 'required|string',
            'project_address' => 'required|string',
            'project_date_start' => 'required|date|before:project_date_end',
            'project_date_end' => 'required|date',
            'project_contact_name' => 'required|string',
            'project_contact_phone' => 'required|string',
            'project_contact_address' => 'required|string',
            'project_contact_website' => 'nullable|string',
            'contract_id' => 'required|integer',
            'select_team' => 'required|integer',
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            // Gán các lỗi vào một biến
            $errors = $validator->errors();

            \Log::error('Validation errors:', $errors->toArray());
            return response()->json(['status' => 422, 'message' => json_encode($errors->toArray())]);
        }

        try {
            $projectData = $request->only([
                'project_name',
                'project_description',
                'project_address',
                'project_date_start',
                'project_date_end',
                'project_contact_name',
                'project_contact_phone',
                'project_contact_address',
                'project_contact_website',
                'contract_id',
            ]);
            $projectData['employee_id'] = AccountController::getEmployeeId();

            $id = DB::table('projects')->insertGetId($projectData);
            $team_id = $request->input('select_team');
            $idTeamProject = DB::table('project_teams')->insert(['project_id' => $id, 'team_id' => $team_id]);
            return response()->json(['status' => 200, 'message' => 'Added a new project']);
        } catch (\Exception $e) {
            if($id) DB::table('projects')->where('project_id', $id)->delete();
            if($idTeamProject) DB::table('project_teams')->where('project_id', $idTeamProject)->delete();
            return response()->json(['status' => 500, 'message' => 'Failed to add a new project', 'error' => $e->getMessage()]);
        }
    }
}
