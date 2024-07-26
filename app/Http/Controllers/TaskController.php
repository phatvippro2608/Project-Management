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
            'taskname' => 'required|string|max:255',
            'request' => 'nullable|string',
            'users' => 'nullable|string',
            's_date' => 'required|date',
            'e_date' => 'required|date|after_or_equal:s_date',
            'id' => 'required|integer|exists:projects,id',
        ]);

        $task = TaskModel::create([
            'task_name' => $validatedData['taskname'],
            'phase_id'=> $validatedData['request'],
            'request' => $validatedData['request'],
            'engineers' => $validatedData['users'],
            'start_date' => $validatedData['s_date'],
            'end_date' => $validatedData['e_date'],
        ]);
        // Create subtasks if any
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'subtask') === 0 && !empty($value)) {
                SubTaskModel::create([
                    'task_id' => $task->task_id,
                    'sub_task_name' => $value,
                ]);
            }
        }
        $tasks = DB::table('tasks')->get();
        $subtasks = DB::table('sub_tasks')
            ->join('tasks', 'sub_tasks.task_id', '=', 'tasks.task_id')
            ->select('sub_tasks.*')
            ->get();
        return response()->json(['tasks' => $tasks, 'subtasks' => $subtasks]);
    }

    public function showTask($id)
    {
        $task = TaskModel::find($id);
        $subtasks = SubTaskModel::where('task_id', $id)->get();
        return response()->json(['task' => $task, 'subtasks' => $subtasks]);
    }

    public function showSubTask($id)
    {
        $subtask = SubTaskModel::find($id);
        return response()->json(['subtask' => $subtask]);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'task_id' => 'required|string|max:255',
            'taskname' => 'required|string|max:255',
            'request' => 'nullable|string',
            'users' => 'nullable|string',
            's_date' => 'nullable|date',
            'e_date' => 'nullable|date|after_or_equal:s_date',
            'id' => 'required|integer|exists:projects,id',
        ]);
        //$id được tách từ task_id để lấy id của task và loại task
        $idtype = explode('_', $validatedData['task_id'])[0];
        $id = explode('_', $validatedData['task_id'])[1];

        if ($idtype == 'task') {
            $task = TaskModel::find($id);
            $task->task_name = $validatedData['taskname'];
            $task->request = $validatedData['request'];
            $task->engineers = $validatedData['users'];
            $task->start_date = $validatedData['s_date'];
            $task->end_date = $validatedData['e_date'];
            $task->save();

            $subtask_ids = [];
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'subtask') === 0 && !empty($value)) {
                    $subtask = SubTaskModel::find(explode('_', $key)[1]);
                    if ($subtask) {
                        $subtask->sub_task_name = $value;
                        $subtask->save();
                        $subtask_ids[] = explode('_', $key)[1];
                        $subtask_ids[] = explode('_', $key)[1];
                    } else {
                        $subtask = SubTaskModel::create([
                            'task_id' => $task->task_id,
                            'sub_task_name' => $value,
                        ]);
                        $subtask_ids[] = $subtask->sub_task_id;
                    }
                }
            }
            SubTaskModel::where('task_id', $task->task_id)->whereNotIn('sub_task_id', $subtask_ids)->delete();
        } else {
            $subtask = SubTaskModel::find($id);
            $subtask->sub_task_name = $validatedData['taskname'];
            $subtask->request = $validatedData['request'];
            $subtask->engineers = $validatedData['users'];
            $subtask->start_date = $validatedData['s_date'];
            $subtask->end_date = $validatedData['e_date'];
            $subtask->save();
        }
        $tasks = DB::table('tasks')->get();
        $subtasks = DB::table('sub_tasks')
            ->join('tasks', 'sub_tasks.task_id', '=', 'tasks.task_id')
            ->select('sub_tasks.*')
            ->get();
        return response()->json(['tasks' => $tasks, 'subtasks' => $subtasks]);
    }

    public function delete($id)
    {
        $idtype = explode('_', $id)[0];
        $id = explode('_', $id)[1];
        if ($idtype == 'task') {
            SubTaskModel::where('task_id', $id)->delete();
            TaskModel::find($id)->delete();
        } else {
            SubTaskModel::find($id)->delete();
        }
        $tasks = DB::table('tasks')->get();
        $subtasks = DB::table('sub_tasks')
            ->join('tasks', 'sub_tasks.task_id', '=', 'tasks.task_id')
            ->select('sub_tasks.*')
            ->get();
        return response()->json(['tasks' => $tasks, 'subtasks' => $subtasks]);
    }
}
