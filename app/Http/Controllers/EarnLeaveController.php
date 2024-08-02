<?php

namespace App\Http\Controllers;

use App\Models\EarnLeaveModel;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class EarnLeaveController extends Controller
{
    function getView()
    {

        $leaveSummaries = EarnLeaveModel::getEmployeeLeaveSummary();
        $employees = EmployeeModel::all();
//        dd($leaveSummaries);
        return view('auth.leave.earn-leave',
                    ['earn_leave' => $leaveSummaries,
                        'employees' => $employees]);
    }
}
