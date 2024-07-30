<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\ProjectModel;
use App\Models\Submenu;
use App\Models\TaskModel;
use App\Models\TeamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getViewDashboard()
    {
        $id_account = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $sql_get_id_employee = "SELECT * FROM employees, account WHERE employees.id_employee = account.id_employee AND id_account = $id_account";
        $id_employee = DB::selectOne($sql_get_id_employee)->id_employee;

        $em_c = count(EmployeeModel::all());
        $team_c = count(TeamModel::all());
        $project_c = count(ProjectModel::all());

        $task_c = DB::table('tasks')->count();
        $sub_c = DB::table('sub_tasks')->count();

        $sql = "SELECT projects.project_id,projects.project_name, TIME(recent_project.created_at) as created_at
        FROM account, recent_project, projects
        WHERE account.id_account = recent_project.id_account AND recent_project.project_id = projects.project_id
        AND account.id_account=$id_account LIMIT 5";
        $recent_project = DB::select($sql);


        $task_sql = "SELECT * FROM tasks, employees, phases
         WHERE tasks.id_employee = employees.id_employee and phases.phase_id = tasks.phase_id
         AND DATE(tasks.start_date) <= CURDATE() AND DATE(tasks.end_date) >= CURDATE()
         AND employees.id_employee=$id_employee";
        $tasks = DB::select($task_sql);


        $subtask_sql = "SELECT * FROM sub_tasks, employees
         WHERE sub_tasks.id_employee = employees.id_employee
         AND DATE(sub_tasks.start_date) <= CURDATE() AND DATE(sub_tasks.end_date) >= CURDATE()
         AND employees.id_employee=$id_employee";
        $subtasks = DB::select($subtask_sql);



        return view('auth.dashboard.dashboard',[
            'em_c'=>$em_c,
            'team_c'=>$team_c,
            'project_c'=>$project_c,
            'task_c'=>$task_c,
            'sub_c'=>$sub_c,
            'recent_project'=>$recent_project,
            'tasks'=>$tasks,
            'subtasks'=>$subtasks,
        ]);
    }
}
