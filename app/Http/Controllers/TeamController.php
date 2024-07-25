<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    function getView()
    {
        return view('auth.project-employee.team.team');
    }
}
