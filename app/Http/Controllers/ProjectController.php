<?php

namespace App\Http\Controllers;

use App\Models\ProjectModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function getView()
    {
        // Lấy danh sách dự án
        $projects = DB::table('projects')
            ->join('contracts', 'contracts.contract_id', '=', 'projects.contract_id')
            ->join('customers', 'customers.customer_id', '=', 'contracts.customer_id')
            ->join('phases', 'phases.phase_id', '=', 'projects.phase_id')
            ->select(
                'projects.*',
                DB::raw("CONCAT(customers.company_name, ' - ', customers.last_name, ' ', customers.first_name) AS customer_info"),
                'phases.phase_name_eng'
            )
            ->orderBy('project_id', 'asc')
            ->get();

        // Lấy danh sách thành viên nhóm cho từng dự án
        foreach ($projects as $project) {
            $project->team_members = DB::table('employees')
                ->distinct()
                ->select('employees.employee_id', 'employees.photo')
                ->join('tasks', 'tasks.employee_id', '=', 'employees.employee_id')
                ->where('tasks.project_id', $project->project_id)
                ->where('employees.employee_id', '<>', $project->employee_id)
                ->get();
        }

        return view('auth.projects.project-list', compact('projects'));
    }


    public function InsPJ(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required',
            'project_description' => 'nullable|string',
            'project_address' => 'nullable|string',
            'project_date_start' => 'nullable|date',
            'project_date_end' => 'nullable|date',
            'project_main_contractor' => 'nullable|string',
            'project_contact_name' => 'nullable|string',
            'project_contact_phone' => 'nullable|string',
            'project_contact_address' => 'nullable|string',
            'project_contact_website' => 'nullable|string',
            'project_contract_amount' => 'nullable|numeric',
        ]);

        try {
            ProjectModel::create($validated);
            return response()->json(['status' => 200, 'message' => 'Thêm dự án thành công']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Thêm dự án thất bại, vui lòng thử lại.', 'error' => $e->getMessage()]);
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
