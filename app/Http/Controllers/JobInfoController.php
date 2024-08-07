<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobInfoController extends Controller
{
    public function getView()
    {
        return view('auth.employees.job');
    }

    public function add()
    {
        return view();
    }

    public function update()
    {
        return view();
    }

    public function delete()
    {
        return view();
    }
}
