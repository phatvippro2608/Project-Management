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
//        dd(LeaveApplicationModel::with('employee', 'leaveType')->get());
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
//        $validated = $request->validate([
//            'employee_id' => 'required|string',
//            'pin' => 'required|string',
//            'leave_type' => 'required|string',
//            'apply_date' => 'required|date',
//            'start_date' => 'required|date',
//            'end_date' => 'required|date',
//            'duration' => 'required|string',
//            'leave_status' => 'required|string',
//        ]);
//
//        $leave_app = LeaveApplicationModel::create($validated);
//
//        return response()->json([
//            'success' => true,
//            "status" => 200,
//            'leave_app' => $leave_app,
//            'message' => 'Holiday added successfully',
//        ]);
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

        // Kiểm tra sự tồn tại của employee_id
        $exists = LeaveApplicationModel::where('employee_id', $request->employee_id)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                "status" => 400,
                'message' => 'Employee already has an application',
            ], 400);
        }

        $leave_app = LeaveApplicationModel::create($validated);

        return response()->json([
            'success' => true,
            "status" => 200,
            'leave_app' => $leave_app,
            'message' => 'Leave application added successfully',
        ]);
    }

    function edit($id)
    {
        $leave_app = LeaveApplicationModel::findOrFail($id);

        return response()->json([
            'leave_app' => $leave_app,
        ]);
    }

    function update(Request $request, $id)
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

        $leave_app = LeaveApplicationModel::findOrFail($id);
        $leave_app->update($validated);

        return response()->json([
            'success' => true,
            'leave_app' => $leave_app,
        ]);
    }

    function destroy($id)
    {
        $leave_app = LeaveApplicationModel::findOrFail($id);
        $leave_app->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leave application deleted successfully',
        ]);
    }
}
