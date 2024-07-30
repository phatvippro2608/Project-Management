<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
class AttendanceController extends Controller
{
    function getView()
    {
        $employees = DB::table('employees')->select('id_employee', 'photo', 'last_name', 'first_name')->get();
        return view('auth.attendance.attendance', compact('employees'));
    }
    function addAttendanceView()
    {
        $employees = DB::table('employees')->select('id_employee', 'photo', 'last_name', 'first_name')->get();
        return view('auth.attendance.add', compact('employees'));
    }
}
