<?php

namespace App\Http\Controllers;

use App\Models\TeamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    private $status = [1 => 'Active', 2 => 'Offine', 3 => 'Locked'];

    function getView()
    {
        $team = DB::table('team')->join('employees', 'employees.id_employee', '=', 'team.created_by')->get();
        return view('auth.project-employee.team.team', ['team' => $team, 'status' => $this->status]);
    }
}
