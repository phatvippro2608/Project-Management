<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
class AttendanceController extends Controller
{
    function getView()
    {
        $attendance = DB::table('attendance')->get();
        $employees = DB::table('employees')->select('id_employee', 'photo', 'last_name', 'first_name')->get();
        return view('auth.attendance.attendance', compact("attendance",'employees'));
    }

    function addAttendanceView()
    {
        $employees = DB::table('employees')->select('id_employee', 'photo', 'last_name', 'first_name')->get();
        return view('auth.attendance.add', compact('employees'));
    }

    function viewAttendanceByID($id)
    {
        $attendance = DB::table('attendance')->where('id_attendance', $id)->get();
        $employees = DB::table('employees')
            ->select('photo', 'last_name', 'first_name')
            ->where('id_employee', $attendance[0]->id_employee)
            ->get();
        //return vè dạng json
        return response()->json(['attendance' => $attendance, 'employees' => $employees]);
    }

    function updateAttendance(Request $request)
    {
        $id = $request->input('id_attendance');
        $id_employee = $request->input('id_employee');
        $date = $request->input('date');
        $sign_in = $request->input('sign_in');
        $sign_out = $request->input('sign_out');
        // Check if the employee has signed in in the same day, same time
        $attendance = DB::table('attendance')
            ->where('id_employee', $id_employee)
            ->where('date', $date)
            ->where('sign_in', $sign_in)
            ->where('id_attendance', '!=', $id)
            ->first();
        if ($attendance) {
            return response()->json(['success' => false, 'message' => 'Error sign in time: employee already signed in this time!']);
        }
        //or check if the employee has signed out time bigger than sign in time in database
        $attendance = DB::table('attendance')
            ->where('id_employee', $id_employee)
            ->where('date', $date)
            ->where('sign_in', '<', $sign_out)
            ->where('id_attendance', '!=', $id)
            ->first();
        if ($attendance) {
            return response()->json(['success' => false, 'message' => 'Error sign out time: employee must be signed out before signed in!']);
        }
        //if sign out time smaller than sign in time, return error
        if ($sign_out < $sign_in) {
            return response()->json(['success' => false, 'message' => 'Error sign out time: sign out time must be bigger than sign in time!']);
        }
        //if employee id not found in database, return error
        $employee = DB::table('employees')->where('id_employee', $id_employee)->first();
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Error employee id: employee not found!']);
        }
        //update attendance
        $attendance = DB::table('attendance')
            ->where('id_attendance', $id)
            ->update([
                'id_employee' => $id_employee,
                'date' => $date,
                'sign_in' => $sign_in,
                'sign_out' => $sign_out
            ]);
        $attendance = DB::table('attendance')
            ->select('attendance.*', 'employees.first_name', 'employees.last_name')
            ->join('employees', 'attendance.id_employee', '=', 'employees.id_employee')
            ->get();

        return response()->json(['success' => true, 'message' => 'Update attendance success', 'attendance' => $attendance]);
    }

    function addAttendance(Request $request)
    {
        $request->validate([
            'id_employee' => 'required',
            'date' => 'required',
            'sign_in' => 'required'
        ]);
        $id_employee = $request->input('id_employee');
        $date = $request->input('date');
        $sign_in = $request->input('sign_in');
        $sign_out = $request->input('sign_out');
        // Check if the employee has signed in in the same day, same time
        $attendance = DB::table('attendance')
            ->where('id_employee', $id_employee)
            ->where('date', $date)
            ->where('sign_in', $sign_in)
            ->first();
        if ($attendance) {
            return response()->json(['success' => false, 'message' => 'Error sign in time: employee already signed in this time!']);
        }
        //or check if the employee has signed out time bigger than sign in time in database
        $attendance = DB::table('attendance')
            ->where('id_employee', $id_employee)
            ->where('date', $date)
            ->where('sign_in', '<', $sign_out)
            ->first();
        if ($attendance) {
            return response()->json(['success' => false, 'message' => 'Error sign out time: employee must be signed out before signed in!']);
        }
        //if sign out time smaller than sign in time, return error
        if ($sign_out < $sign_in) {
            return response()->json(['success' => false, 'message' => 'Error sign out time: sign out time must be bigger than sign in time!']);
        }
        //if employee id not found in database, return error
        $employee = DB::table('employees')->where('id_employee', $id_employee)->first();
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Error employee id: employee not found!']);
        }


        $attendance = DB::table('attendance')->insert([
            'id_employee' => $id_employee,
            'date' => $date,
            'sign_in' => $sign_in,
            'sign_out' => $sign_out
        ]);
        return response()->json(['success' => true, 'message' => 'Add new attendance success']);
    }

    function deleteAttendance(Request $request)
    {
        $id = $request->input('id_attendance');
        $attendance = DB::table('attendance')->where('id_attendance', $id)->delete();
        $attendance = DB::table('attendance')
            ->select('attendance.*', 'employees.first_name', 'employees.last_name')
            ->join('employees', 'attendance.id_employee', '=', 'employees.id_employee')
            ->get();

        return response()->json(['success' => true, 'message' => 'Delete attendance success', 'attendance' => $attendance]);
    }
}
