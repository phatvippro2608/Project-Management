<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    function getViewProfile()
    {
        return view('auth.profile',

        );
    }
}
