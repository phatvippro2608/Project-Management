<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    function getView()
    {
        $data = new EmployeeModel();
        return view('auth.employees.employees',[
            'data' => $data->getEmployee()
        ]
        );
    }


}
