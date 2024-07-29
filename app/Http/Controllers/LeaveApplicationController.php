<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplicationModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveApplicationController extends Controller
{
    function getView()
    {
        $model = new LeaveApplicationModel();
        $employee_name = $model->getEmployeeName();
        $leave_type = $model->getLeaveTypes();
        return view(
            'auth.leave.leave-application',
            [
            'leave_types'=> LeaveApplicationModel::all(),
            'employee_name' => $employee_name,
            'leave_type' => $leave_type,
                'leave_applications' => LeaveApplicationModel::with('employee', 'leaveType')->get(),
            ]);
    }

    function add(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|string',
            'pin' => 'required|string',
            'leave_type' => 'required|string',
            'apply_date' => 'required|date',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'duration' => 'required|string',
            'leave_status' => 'required|string',
        ]);

        $leave_app = LeaveApplicationModel::create($validated);

        return response()->json([
            'success' => true,
            "status" => 200,
            'leave_app' => $leave_app,
            'message' => 'Holiday added successfully',
        ]);
    }
}
