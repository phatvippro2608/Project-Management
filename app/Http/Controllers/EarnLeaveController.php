<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EarnLeaveController extends Controller
{
    function getView()
    {
        return view('auth.leave.earn-leave');
    }
}
