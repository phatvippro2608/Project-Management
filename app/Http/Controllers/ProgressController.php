<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\ProgressModel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ProgressController extends Controller
{
    function getViewHasID($id)
    {

        $employees = DB::table('employees')->select('employees.employee_id','employee_code', 'photo', 'last_name', 'first_name')
        ->join('team_details', 'employees.employee_id', '=', 'team_details.employee_id')
        ->join('project_teams', 'team_details.team_id', '=', 'project_teams.team_id')
        ->join('project_locations', 'project_teams.project_id', '=', 'project_locations.project_id')
        ->where('project_locations.project_location_id', $id)
        ->get();
        return view('auth.progress.progress', compact('employees', 'id'));
    }

    public function updateItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

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
