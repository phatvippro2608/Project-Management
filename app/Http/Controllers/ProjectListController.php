<?php

namespace App\Http\Controllers;

use App\Models\ProjectListModel;
use Illuminate\Http\Request;
use function Laravel\Prompts\table;
use Illuminate\Support\Facades\DB;

class ProjectListController extends Controller
{
    function getView()
    {

        // Đang triển khai
        $inprogress_projects = DB::table('projects')->where('phase_id', 1)->get();

        // Nghiệm thu
        $inspection_projects = DB::table('projects')->where('phase_id', 2)->get();

        // Khảo sát
        $survey_projects = DB::table('projects')->where('phase_id', 3)->get();

        // Hỗ trợ
        $support_projects = DB::table('projects')->where('phase_id', 4)->get();

        // Đóng
        $closed_projects = DB::table('projects')->where('phase_id', 5)->get();

        return view('auth.projects.project-list',
            compact('inprogress_projects',
                'inspection_projects',
                'survey_projects',
                'support_projects',
                'closed_projects'));
    }

    public function InsPJ(Request $request)
    {
        $validated = $request->validate([
            'project_name' => 'required',
            'project_description' => 'nullable|string',
            'project_address' => 'nullable|string',
            'project_main_contractor' => 'nullable|string',
            'project_contact_name' => 'nullable|string',
            'project_contact_website' => 'nullable|string',
            'project_contact_phone' => 'nullable|string',
            'project_contact_adress' => 'nullable|string',
            'project_date_start' => 'nullable|date',
            'project_date_end' => 'nullable|date',
        ]);

        ProjectListModel::create($validated);

        return redirect('auth.projects.project-list')->with('success', 'Thêm dự án thành công');
    }
}
