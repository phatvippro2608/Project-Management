<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\ProgressModel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ProgressController extends Controller
{
    function getView()
    {
        return view('auth.progress.progress');
    }

    function getViewHasID($id)
    {
        $project = DB::table('projects')->where('project_id', $id)->exists();
        if (!$project) {
            abort(404, 'Project not found');
        }
        $tasks = DB::table('tasks')
            ->leftJoin('employees', 'tasks.employee_id', '=', 'employees.employee_id')
            ->select('tasks.*', 'employees.last_name', 'employees.first_name')
            ->where('project_id', $id)
            ->get();
        $employees = DB::table('employees')->select('employee_id', 'photo', 'last_name', 'first_name')->get();
        return view('auth.progress.progress', compact('tasks', 'employees', 'id'));
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
