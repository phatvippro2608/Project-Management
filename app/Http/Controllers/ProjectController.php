<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function getViewProjectList()
    {
        return view('auth.projects.project_list');
    }
}
