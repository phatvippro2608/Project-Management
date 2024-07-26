<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryManagementController extends Controller
{
    function getView(){
        return view('auth.inventory.dashboard');
    }
}
