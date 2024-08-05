<?php

namespace App\Http\Controllers;

use App\Models\ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
