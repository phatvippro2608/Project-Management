<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\LeaveApplicationModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveReportsController extends Controller
{
    public function getView()
    {
        $employees = EmployeeModel::all();
        return view('auth.leave.leave-report', compact('employees'));
    }

    public function searchReports(Request $request)
    {
        $monthYear = $request->input('report_month_year');
        $employeeId = $request->input('employee_id');

        $reports = LeaveApplicationModel::with(['leaveType', 'employee'])
            ->where('employee_id', $employeeId)
            ->whereYear('start_date', '=', Carbon::parse($monthYear)->year)
            ->whereMonth('start_date', '=', Carbon::parse($monthYear)->month)
            ->get();

        return response()->json($reports);
    }

    public function approveLeaveApplication($id)
    {
        $leaveApplication = LeaveApplicationModel::findOrFail($id);
        $leaveApplication->leave_status = 'approved';
        $leaveApplication->apply_date = Carbon::now();
        $leaveApplication->save();

        return response()->json([
            'success' => true,
            'message' => 'Leave request approved successfully',
            'leave_application' => $leaveApplication
        ]);
    }
    public function destroy($id)
    {
        $holiday = LeaveApplicationModel::findOrFail($id);
        $holiday->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leave reports deleted successfully',
        ]);
    }

    public function getLeaveApplications()
    {
        $leaveApplications = LeaveApplicationModel::with('employee', 'leaveType')->get();
        return response()->json($leaveApplications);
    }
}
