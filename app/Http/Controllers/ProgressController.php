<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    function getView()
    {
        $sql = DB::table('projects')->get();
        return view('auth.progress.progress',compact('sql'));
    }
}
