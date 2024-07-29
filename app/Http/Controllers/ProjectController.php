<?php

namespace App\Http\Controllers;

use App\Models\ProjectModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    function getView()
    {
        // Đang triển khai
        $inprogress_projects = DB::table('projects')->where('phase_id', 1)->get();
        $inprogress_project_count = $inprogress_projects->count();

        // Nghiệm thu
        $inspection_projects = DB::table('projects')->where('phase_id', 2)->get();
        $inspection_projects_count = $inspection_projects->count();

        // Khảo sát - thiết kế
        $survey_projects = DB::table('projects')->where('phase_id', 3)->get();
        $survey_projects_count = $survey_projects->count();

        // Hỗ trợ
        $support_projects = DB::table('projects')->where('phase_id', 4)->get();
        $support_projects_count = $support_projects->count();

        // Đóng
        $closed_projects = DB::table('projects')->where('phase_id', 5)->get();
        $closed_projects_count = $closed_projects->count();

        return view('auth.projects.project-list',
            compact('inprogress_projects',
                'inprogress_project_count',
                'inspection_projects',
                'inspection_projects_count',
                'survey_projects',
                'survey_projects_count',
                'support_projects',
                'support_projects_count',
                'closed_projects',
                'closed_projects_count'
            ));
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
