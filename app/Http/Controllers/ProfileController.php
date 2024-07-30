<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\EmployeeModel;
use App\Models\ProfileModel;
use App\StaticString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    function getViewProfile(Request $request)
    {
        $id_employee = $request->id_employee;
        $data = new ProfileModel();
        $employee = new EmployeeModel();
        $employ_detail = EmployeeModel::where('id_employee', $id_employee)->first();
        return view('auth.employees.profile', ['profiles' => $data->getProfile(),
                                                    'dataEmployee' => $employee->getAllJobDetails(),
            'employ_detail' => $employ_detail]);

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
            $data->id_employee =  $request->id_employee;
            $data->updateProfile();
            return json_encode((object)["status" => 200, "message" => "Action Success"]);
        } catch (\Exception $e) {
            return json_encode((object)["status" => 400, "message" => "Action Faild"]);
        }

    }


    function changePassword(Request $request)
    {

        $currentPassword = $request->input('currentPassword');
        $newPassword = $request->input('newPassword');
        $renewPassword = $request->input('renewPassword');

        if ($newPassword !== $renewPassword) {
            return json_encode((object)["status" => 400, "message" => "Mật khẩu mới không khớp"]);
        }

        $id_account = $request->session()->get(StaticString::ACCOUNT_ID);
        $account = AccountModel::where('id_account', $id_account)->first();

        if (!$account || !password_verify($currentPassword, $account->password)) {
            return json_encode((object)["status" => 400, "message" => "Mật khẩu hiện tại không đúng"]);
        }

        $hashPass = password_hash($renewPassword, PASSWORD_BCRYPT);
        $account->password = $hashPass;
        $account->save();

        if ($account->save()) {
            return json_encode((object)["status" => 200, "message" => "Đổi mật khẩu thành công"]);
        }
        return json_encode((object)["status" => 500, "message" => "Đổi mật khẩu thất bại"]);
    }
}
