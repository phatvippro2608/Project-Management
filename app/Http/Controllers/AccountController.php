<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\EmployeeModel;
use App\StaticString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

    static function permission()
    {
        return Session::get(StaticString::PERMISSION);
    }

    function add(Request $request)
    {

    }
}
