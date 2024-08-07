<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    function getViewDashboard()
    {
        return view('auth.lms.workshop');
    }
}
