<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    function getViewProgress()
    {
        return view('auth.projects.progress');
    }

    function getViewExpenses()
    {
        return view('auth.projects.expenses');
    }
}
