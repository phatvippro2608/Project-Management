<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    function getView()
    {
        $tasks = DB::table('tasks')->get();
        //get all sub_tasks from sub_tasks table where task_id is equal to task_id from tasks table
        $subtasks = DB::table('sub_tasks')
            ->join('tasks', 'sub_tasks.task_id', '=', 'tasks.task_id')
            ->select('sub_tasks.*')
            ->get();

        return view('auth.progress.progress', compact('tasks', 'subtasks'));
    }
}
