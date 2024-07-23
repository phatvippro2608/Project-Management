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
        $projects = ProjectListModel::all();

        // Đang triển khai
        $inprogress_projects = DB::table('projects')->where('project_status', 1)->get();

        // Nghiệm thu
        $inspection_projects = DB::table('projects')->where('project_status', 2)->get();

        // Khảo sát
        $survey_projects = DB::table('projects')->where('project_status', 3)->get();

        // Hỗ trợ
        $support_projects = DB::table('projects')->where('project_status', 4)->get();

        // Đóng
        $closed_projects = DB::table('projects')->where('project_status', 5)->get();

        return view('auth.projects.project-list',
            compact('projects',
                'inprogress_projects',
                'inspection_projects',
                'survey_projects',
                'support_projects',
                'closed_projects'));
    }
}
