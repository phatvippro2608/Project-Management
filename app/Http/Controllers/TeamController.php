<?php

namespace App\Http\Controllers;

use App\Models\TeamModel;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    function getView()
    {
        $team = TeamModel::all();
        return view('auth.project-employee.team.team');
    }
}
