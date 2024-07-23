<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getViewDashboard()
    {
        return view('auth.dashboard.dashboard');
    }


}
