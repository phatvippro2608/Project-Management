<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private $status = [1=>'Active', 2 => 'Offine', 3 => 'Locked'];
    function getView()
    {
        $account = AccountModel::getAll();
        $employees = EmployeeModel::all();
        $status = $this->status;
        return view('auth.account.account', ['account' => $account, 'employees' => $employees, 'status' => $this->status]);
    }

    function add(Request $request)
    {

    }
}
