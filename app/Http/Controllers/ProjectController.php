<?php

namespace App\Http\Controllers;


use App\Models\ProjectModel;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    static public function getView()
    {
        $project = DB::table('projects')
            ->join('contracts', 'contracts.contract_id', '=', 'projects.contract_id')
            ->join('customers', 'customers.customer_id', '=', 'contracts.customer_id')
            ->join('phases', 'phases.phase_id', '=', 'projects.phase_id')
            ->join('project_teams', 'project_teams.project_id', '=', 'projects.project_id')
            ->select(
                'project_teams.*',
                'projects.*',
                DB::raw("CONCAT(customers.company_name, ' - ', customers.last_name, ' ', customers.first_name) AS customer_info"),
                'phases.phase_name_eng'
            )
            ->orderBy('project_teams.project_id', 'asc')
            ->get();
        foreach ($project as $item) {
            $item->team_members = DB::table('team_details')
                ->join('employees', 'employees.employee_id', '=', 'team_details.employee_id')
                ->where('team_id', $item->team_id)
                ->orderBy('team_permission', 'asc')
                ->get();
        }
        $teams = DB::table('teams')->get();
        $contracts = DB::table('contracts')
            ->leftjoin('projects', 'contracts.contract_id', '=', 'projects.contract_id')
            ->whereNull('projects.contract_id')->select('contracts.contract_id', 'contracts.contract_name')->get();
        return view('auth.projects.project-list', compact('project', 'teams', 'contracts'));
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

    public function getAttachmentView(Request $request, $project_id){
        $contracts_id = DB::table('projects')->where('project_id', $project_id)->value('contract_id');
        $contract = DB::table('contracts')->where('contract_id', $contracts_id)->first();
        $company = DB::table('customers')->where('customer_id', $contract->customer_id)->value('company_name');
        $locations = DB::table('project_locations')->where('project_id', $project_id)->get();

        return view('auth.projects.project-attachments',[
            'project_id' => $project_id,
            'contract' => $contract,
            'company' => $company,
            'locations' => $locations
        ]);
    }
    public function getDateAttachments(Request $request, $project_id, $project_location_id) {
        $dates = DB::table('projects')
            ->where('project_id', $project_id)
            ->select('project_date_start','project_date_end')
            ->first();
        $startDate = Carbon::parse($dates->project_date_start);
        $endDate = Carbon::parse($dates->project_date_end);

        $dateRange = [];

        while ($startDate->lte($endDate)) {
            $dateRange[] = $startDate->format('Y-m-d');
            $startDate->addDay();
        }

        return json_encode($dateRange);
    }

    public function getFileAttachments(Request $request, $project_id, $project_location_id, $date){
        $files = DB::table('task_files')
            ->where('project_location_id', $project_location_id)
            ->whereDate('date', $date)
            ->get();

        $images = DB::table('task_images')
            ->where('project_location_id', $project_location_id)
            ->whereDate('date', $date)
            ->get();

        return [
            'files' => json_encode($files),
            'images' => json_encode($images),
        ];
    }


}
