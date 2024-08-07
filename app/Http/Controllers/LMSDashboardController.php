<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LMSDashboardController extends Controller
{
    public function getView(){
        return view('auth.lms.lms-dashboard');
    }
}
