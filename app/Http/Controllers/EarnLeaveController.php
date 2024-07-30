<?php

namespace App\Http\Controllers;

use App\Models\EarnLeaveModel;
use Illuminate\Http\Request;

class EarnLeaveController extends Controller
{
    function getView()
    {

        $leaveSummaries = EarnLeaveModel::getEmployeeLeaveSummary();
//        dd($leaveSummaries);
        return view('auth.leave.earn-leave',
                    ['earn_leave' => $leaveSummaries]);
    }
}
