<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    function getView()
    {
        $account = AccountModel::getAll();
        $employees = EmployeeModel::all();
        return view('auth.account.account', ['account' => $account, 'employees' => $employees]);
    }

    function add(Request $request)
    {

    }
}
