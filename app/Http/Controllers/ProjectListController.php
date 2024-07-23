<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectListController extends Controller
{
    function getView()
    {
        return view('auth.projects.project-list');
    }
}
