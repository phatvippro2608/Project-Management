<?php

namespace App\Http\Controllers;

use App\Models\LeaveApplicationModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveApplicationController extends Controller
{
    function getView()
    {
//        $data = \Illuminate\Support\Facades\DB::table('accounts')
//            ->join('employees', 'accounts.employee_id', '=', 'employees.employee_id')
//            ->join('job_details', 'job_details.employee_id', '=', 'employees.employee_id')
//            ->where(
//                'account_id',
//                \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID),
//            )
//            ->first();
//
//        dd($data);




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
        $validated = $request->validate([
            'employee_id' => 'required|string',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'duration' => 'required|string',
        ]);

        // Kiểm tra xung đột
        $conflicts = DB::table('leave_applications')
            ->where('employee_id', $request->employee_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function($query) use ($request) {
                        $query->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($conflicts) {
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'The employee already has a leave application within the selected dates.',
            ]);
        }

        $validated['leave_status'] = "Not Approved";
        $leave_app = LeaveApplicationModel::create($validated);

        return response()->json([
            'success' => true,
            "status" => 200,
            'message' => 'Leave application added successfully',
        ]);
    }
    function edit($id)
    {
        $leave_app = LeaveApplicationModel::with('employee', 'leaveType')->findOrFail($id);
//        dd($leave_app);
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
