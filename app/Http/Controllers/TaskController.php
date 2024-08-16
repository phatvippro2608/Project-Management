<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\TaskModel;
use App\Http\Controllers\AccountController;

class TaskController extends Controller
{
    function getView()
    {
        return view('auth.task.task-management');
    }

    public function showPhaseTasks($phase)
    {

        return view('auth.task.phase-task');
    }

    public function showTaskSubtasks($task)
    {

        return view('auth.task.task-subtask');
    }
    // Của Bình
    public function showTask(Request $request)
    {
        $tasks = DB::table('tasks')
            ->leftJoin('employees', 'tasks.employee_id', '=', 'employees.employee_id')
            ->select('tasks.*', 'employees.last_name', 'employees.first_name')
            ->where('project_location_id', $request->id)
            ->get();
        return response()->json(['success' => true,'tasks' => $tasks]);
    }

    public function getTask(Request $request)
    {
        $tasks = DB::table('tasks')
            ->leftJoin('employees', 'tasks.employee_id', '=', 'employees.employee_id')
            ->select('tasks.*', 'employees.last_name', 'employees.first_name', 'employees.employee_code')
            ->where('task_id', $request->id)
            ->get();
        $subtasks = DB::table('tasks')
            ->where('parent_id', $request->id)
            ->get();
        return response()->json(['success' => true,'tasks' => $tasks, 'subtasks' => $subtasks]);
    }

    public function create(Request $request)
    {
        try {
            
            $validatedData = $request->validate([
                'id' => 'required|string',
                'taskname' => 'required|string|max:255',
                'request' => 'nullable|string',
                'employee_id' => 'required|string',
                's_date' => 'required|date',
                'e_date' => 'required|date|after_or_equal:s_date',
            ]);
            $progress = 0;
            if($request->has('allmarkdone')){
                $progress = 1;
            }
            $empl_allow = DB::table('employees')->select('employees.employee_id')
                ->join('team_details', 'employees.employee_id', '=', 'team_details.employee_id')
                ->join('project_teams', 'team_details.team_id', '=', 'project_teams.team_id')
                ->join('project_locations', 'project_teams.project_id', '=', 'project_locations.project_id')
                ->where('project_locations.project_location_id', $validatedData['id'])
                ->get()->toArray();
            $empl_allow_ids = array_map(function($empl) {
                    return $empl->employee_id;
            }, $empl_allow);
            if(!in_array($validatedData['employee_id'], $empl_allow_ids)){
                return response()->json(['success' => false,'message' => 'Employee is not in this project']);
            }
            $leaders = DB::table('employees')->select('employees.employee_id')
            ->join('team_details', 'employees.employee_id', '=', 'team_details.employee_id')
            ->join('project_teams', 'team_details.team_id', '=', 'project_teams.team_id')
            ->join('project_locations', 'project_teams.project_id', '=', 'project_locations.project_id')
            ->where('project_locations.project_location_id', $validatedData['id'])
            ->where('team_details.team_permission', 1)
            ->get();
            $data = \Illuminate\Support\Facades\DB::table('accounts')
                ->join('employees', 'accounts.employee_id', '=', 'employees.employee_id')
                ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
                ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')

                ->where(
                    'accounts.account_id',
                    \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
                )
                ->first();
            $empl_id = (int) $data->employee_id;
            $leader_arr = [];
            foreach ($leaders as $l) {
                array_push($leader_arr, $l->employee_id);
            }
            if (!in_array($empl_id, $leader_arr) && !in_array(AccountController::permissionStr(), ['super', 'admin', 'project_manager'])){
                return response()->json(['success' => false,'message' => 'You do not have permission to add task']);
            }

            $task = TaskModel::create([
                'task_name' => $validatedData['taskname'],
                'project_location_id' => $validatedData['id'],
                'request' => $validatedData['request'],
                'employee_id' => $validatedData['employee_id'],
                'start_date' => $validatedData['s_date'],
                'end_date' => $validatedData['e_date'],
                'progress' => $progress,
            ]);
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'subtask') === 0 && !empty($value)) {
                    $progress = 0;
                    if($request->has('allmarkdone') || $request->has('markdone_'.explode('_', $key)[1])){
                        $progress = 1;
                    }
                    TaskModel::create([
                        'task_name' => $value,
                        'employee_id' => $validatedData['employee_id'],
                        'project_location_id' => $validatedData['id'],
                        'start_date' => $validatedData['s_date'],
                        'end_date' => $validatedData['s_date'],
                        'parent_id' => $task->task_id,
                        'progress' => $progress,
                    ]);
                }
            }
            if(!$request->has('allmarkdone')){
                $subtasks = DB::table('tasks')->where('parent_id', $task->task_id)->get();
                if(count($subtasks)){
                    $progress = 0;
                    foreach ($subtasks as $subtask) {
                        $progress += $subtask->progress;
                    }
                    TaskModel::where('task_id', $task->task_id)->update([
                        'progress' => $progress / count($subtasks),
                    ]);
                }
            }

            $tasks = DB::table('tasks')
                ->leftJoin('employees', 'tasks.employee_id', '=', 'employees.employee_id')
                ->select('tasks.*', 'employees.last_name', 'employees.first_name')
                ->where('project_location_id', $validatedData['id'])
                ->get();
            return response()->json(['success' => true,'message' => 'Add new task success','tasks' => $tasks]);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => e.getMessage()]);
        }
    }

    public function update(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|string',
                'task_id' => 'required|string',
                'taskname' => 'required|string|max:255',
                'request' => 'nullable|string',
                'employee_id' => 'required|string',
                's_date' => 'required|date',
                'e_date' => 'required|date|after_or_equal:s_date',
            ]);
            
            $empl_allow = DB::table('employees')->select('employees.employee_id')
                ->join('team_details', 'employees.employee_id', '=', 'team_details.employee_id')
                ->join('project_teams', 'team_details.team_id', '=', 'project_teams.team_id')
                ->join('project_locations', 'project_teams.project_id', '=', 'project_locations.project_id')
                ->where('project_locations.project_location_id', $validatedData['id'])
                ->get()->toArray();
            $empl_allow_ids = array_map(function($empl) {
                    return $empl->employee_id;
            }, $empl_allow);
            if(!in_array($validatedData['employee_id'], $empl_allow_ids)){
                return response()->json(['success' => false,'message' => 'Employee is not in this project']);
            }

            $progress = 0;
            if($request->has('allmarkdone')){
                $progress = 1;
            }
            TaskModel::where('task_id', $validatedData['task_id'])->update([
                'task_name' => $validatedData['taskname'],
                'request' => $validatedData['request'],
                'employee_id' => $validatedData['employee_id'],
                'start_date' => $validatedData['s_date'],
                'end_date' => $validatedData['e_date'],
                'progress' => $progress,
            ]);
            $sbtask_id = TaskModel::where('parent_id', $validatedData['task_id'])->pluck('task_id')->toArray();

            //update subtask
            $subtask_id = [];
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'subtask') === 0 && !empty($value)) {
                    $progress = 0;
                    if($request->has('allmarkdone') || $request->has('markdone_'.explode('_', $key)[1])){
                        $progress = 1;
                    }
                    if (strpos(explode('_', $key)[1], 'n') === 0) {
                        $sub=TaskModel::create([
                            'task_name' => $value,
                            'parent_id' => $validatedData['task_id'],
                            'project_location_id' => $validatedData['id'],
                            'progress' => $progress,
                        ]);
                        array_push($subtask_id, $sub->task_id);
                    } else {
                        TaskModel::where('task_id', explode('_', $key)[1])->update([
                            'task_name' => $value,
                            'progress' => $progress,
                        ]);
                        array_push($subtask_id, explode('_', $key)[1]);
                    }
                }
            }
            $delete_id = array_diff($sbtask_id, $subtask_id);
            TaskModel::whereIn('task_id', $delete_id)->delete();

            if(!$request->has('allmarkdone')){
                $subtasks = DB::table('tasks')->where('parent_id', $validatedData['task_id'])->get();
                if(count($subtasks)){
                    $progress = 0;
                    foreach ($subtasks as $subtask) {
                        $progress += $subtask->progress;
                    }
                    TaskModel::where('task_id', $validatedData['task_id'])->update([
                        'progress' => $progress / count($subtasks),
                    ]);
                }
            }
            $par_id=TaskModel::where('task_id', $validatedData['task_id'])->pluck('parent_id')->first();
            if(!empty($par_id)){
                $subtasks = DB::table('tasks')->where('parent_id', $par_id)->get();
                if(count($subtasks)){
                    $progress = 0;
                    foreach ($subtasks as $subtask) {
                        $progress += $subtask->progress;
                    }
                    TaskModel::where('task_id', $par_id)->update([
                        'progress' => $progress / count($subtasks),
                    ]);
                }
            }

            $tasks = DB::table('tasks')
                ->leftJoin('employees', 'tasks.employee_id', '=', 'employees.employee_id')
                ->select('tasks.*', 'employees.last_name', 'employees.first_name')
                ->where('project_location_id', $validatedData['id'])
                ->get();
            return response()->json(['success' => true,'message' => 'Update task success','tasks' => $tasks]);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => e.getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $location_id = TaskModel::where('task_id', $request->id)->pluck('project_location_id')->first();
            $leaders = DB::table('employees')->select('employees.employee_id')
            ->join('team_details', 'employees.employee_id', '=', 'team_details.employee_id')
            ->join('project_teams', 'team_details.team_id', '=', 'project_teams.team_id')
            ->join('project_locations', 'project_teams.project_id', '=', 'project_locations.project_id')
            ->where('project_locations.project_location_id', $location_id)
            ->where('team_details.team_permission', 1)
            ->get();
            $data = \Illuminate\Support\Facades\DB::table('accounts')
                ->join('employees', 'accounts.employee_id', '=', 'employees.employee_id')
                ->join('contacts', 'employees.contact_id', '=', 'contacts.contact_id')
                ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')

                ->where(
                    'accounts.account_id',
                    \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
                )
                ->first();
            $empl_id = (int) $data->employee_id;
            $leader_arr = [];
            foreach ($leaders as $l) {
                array_push($leader_arr, $l->employee_id);
            }
            if (!in_array($empl_id, $leader_arr) && !in_array(AccountController::permissionStr(), ['super', 'admin', 'project_manager'])){
                return response()->json(['success' => false,'message' => 'You do not have permission to delete task']);
            }
            TaskModel::find($request->id)->delete();
            TaskModel::where('parent_id', $request->id)->delete();

            return response()->json(['success' => true,'message' => 'Delete task success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false,'message' => e.getMessage()]);
        }
    }
}
