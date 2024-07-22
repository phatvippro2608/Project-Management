<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    function getView()
    {
        return view('auth.account.account');
    }

    function add(Request $request)
    {
        
    }
}
