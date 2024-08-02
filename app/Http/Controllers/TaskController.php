<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\TaskModel;
use App\Models\SubTaskModel;

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
    public function getTasksData()
    {
        $tasks = DB::table('tasks')->get();
        $subtasks = DB::table('sub_tasks')
            ->join('tasks', 'sub_tasks.task_id', '=', 'tasks.task_id')
            ->select('sub_tasks.*')
            ->get();
        return response()->json(['tasks' => $tasks, 'subtasks' => $subtasks]);
    }

    public function create(Request $request)
    {
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
            $progress = 100;
        }

        $task = TaskModel::create([
            'task_name' => $validatedData['taskname'],
            'project_id' => $validatedData['id'],
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
                    $progress = 100;
                }
                TaskModel::create([
                    'task_name' => $value,
                    'project_id' => $validatedData['id'],
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
            ->where('project_id', $validatedData['id'])
            ->get();
        return response()->json(['success' => true,'message' => 'Add new task success','tasks' => $tasks]);
    }

    public function update(Request $request)
    {
        // return $request;
        $validatedData = $request->validate([
            'id' => 'required|string',
            'task_id' => 'required|string',
            'taskname' => 'required|string|max:255',
            'request' => 'nullable|string',
            'employee_id' => 'required|string',
            's_date' => 'required|date',
            'e_date' => 'required|date|after_or_equal:s_date',
        ]);
        //nếu tồn tại markdone thì progress = 100
        $progress = 0;
        if($request->has('allmarkdone')){
            $progress = 100;
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
                    $progress = 100;
                }
                if (strpos(explode('_', $key)[1], 'n') === 0) {
                    $sub=TaskModel::create([
                        'task_name' => $value,
                        'parent_id' => $validatedData['task_id'],
                        'project_id' => $validatedData['id'],
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

        $tasks = DB::table('tasks')
            ->leftJoin('employees', 'tasks.employee_id', '=', 'employees.employee_id')
            ->select('tasks.*', 'employees.last_name', 'employees.first_name')
            ->where('project_id', $validatedData['id'])
            ->get();
        return response()->json(['success' => true,'message' => 'Add new task success','tasks' => $tasks]);
    }

    public function delete($id)
    {
        TaskModel::find($id)->delete();
        TaskModel::where('parent_id', $id)->delete();

        return response()->json(['success' => true,'message' => 'Delete task success']);
    }
}
