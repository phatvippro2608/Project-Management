<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeesController extends Controller
{
    function getView()
    {
        $data = new EmployeeModel();
        $data = $data->getEmployee();
        return view('auth.employees.employees',[
            'data' => $data
        ]
        );
    }


}
