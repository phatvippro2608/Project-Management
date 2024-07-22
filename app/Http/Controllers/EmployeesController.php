<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    function getView()
    {
        return view('auth.employees.employees');
    }
}
