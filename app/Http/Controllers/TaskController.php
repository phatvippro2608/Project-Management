<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    function getView() {
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
}
