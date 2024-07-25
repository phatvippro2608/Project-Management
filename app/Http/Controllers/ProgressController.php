<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\ProgressModel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class ProgressController extends Controller
{
    function getView()
    {
        $tasks = DB::table('tasks')->get();
        $subtasks = DB::table('sub_tasks')
            ->join('tasks', 'sub_tasks.task_id', '=', 'tasks.task_id')
            ->select('sub_tasks.*')
            ->get();

        return view('auth.progress.progress', compact('tasks', 'subtasks'));
    }

    public function updateItem(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'item_id' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Tách loại mục và ID từ item_id
        $parts = explode('_', $request->item_id);
        if (count($parts) !== 2) {
            return response()->json(['success' => false, 'message' => 'Invalid item ID format']);
        }

        $itemType = $parts[0];
        $itemId = $parts[1];

        if ($itemType === 'task') {
            $updated = DB::table('pippoiov_db_ventech.tasks')
                ->where('task_id', $itemId)
                ->update([
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Task updated successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Task not found or no change']);
            }
        }

        if ($itemType === 'subtask') {
            $updated = DB::table('pippoiov_db_ventech.sub_tasks')
                ->where('sub_task_id', $itemId)
                ->update([
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                ]);

            if ($updated) {
                return response()->json(['success' => true, 'message' => 'Subtask updated successfully']);
            } else {
                return response()->json(['success' => false, 'message' => 'Subtask not found or no change']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Invalid item type']);
    }



}
