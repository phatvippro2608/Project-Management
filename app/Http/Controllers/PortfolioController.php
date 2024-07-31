<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    //
    function getView()
    {
        $sql = DB::table('employees')->get();
        return view('auth.portfolio.portfolio', ['sql'=>$sql]);
    }
}
