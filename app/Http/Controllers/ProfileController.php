<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\ProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    function getViewProfile()
    {
        $data = new ProfileModel();
        $employee = new EmployeeModel();
//        dd($data->getProfile());
        return view('auth.employees.profile', ['profiles' => $data->getProfile(),
                                                    'dataEmployee' => $employee->getAllJobDetails()]);
    }
    function postProfile(Request $request)
    {
        try {
            $data = new ProfileModel();
            $id_account = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
            $data->first_name = $request->first_name;
            $data->last_name = $request->last_name;
            $data->position_name = $request->position_name;
            $data->country_name = $request->country_name;
            $data->permanent_address = $request->permanent_address;
            $data->phone_number = $request->phone_number;
            $data->email = $request->email;
            $data->id_account = $id_account;
            $data->updateProfile();
            return json_encode((object)["status" => 200, "message" => "Action Success"]);
        } catch (\Exception $e) {
            return json_encode((object)["status" => 400, "message" => "Action Faild"]);
        }

    }

}
