<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    function getView()
    {
        return view('auth.attendance.attendance');
    }
}
