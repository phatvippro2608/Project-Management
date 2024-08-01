<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\EmployeeModel;
use App\StaticString;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    private $status = [1=>'Active', 2 => 'Offine', 3 => 'Locked'];
    private $permission = [1=>'Super Admin', 2 => 'Admin', 0 => 'User'];
    function getView(Request $request)
    {
        $perPage = intval(env('ITEM_PER_PAGE'));
        $keyword = $request->input('keyw', '');
        $account = AccountModel::getAll($keyword);
        $employees = EmployeeModel::all();
        $status = $this->status;
        return view('auth.account.account', ['account' => $account, 'employees' => $employees, 'status' => $this->status, 'permission' => $this->permission]);
    }

    static function permission()
    {
        return Session::get(StaticString::PERMISSION);
    }

    function randomUserPwd($max=5) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $max; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    static function toAttrJson($data, $list = []){
        if (count($list)){
            $tmp = array();
            $data = (array)$data;
            foreach ($list as $key){
                $tmp[$key] = $data[$key];
            }
            return json_encode($tmp);
        }
        return json_encode($data);
    }

    public static function status($message, $code)
    {
        return json_encode((object)["status" => $code, "message" => $message]);
    }

    function add(Request $request)
    {
        $id_employee = $request->input('id_employee');
        $username = $request->input('username');
        $password = $request->input('password');
        $status = $request->input('status');
        $permission = $request->input('permission');
        $auto_pwd = $request->input('auto_pwd');

        if($auto_pwd == 'true')
            $password = $this->randomUserPwd(20);
        $hashPass = password_hash($password, PASSWORD_BCRYPT);
        $i = [
            'id_employee' => $id_employee,
            'username' => $username,
            'email' => null,
            'password' => $hashPass,
            'status' => $status,
            'permission' => $permission,
        ];
        if(AccountModel::insert($i)){
            return $this->status('Thêm thành công', 200);
        };
        return $this->status('Thêm thất bại', 500);
    }

    function update(Request $request)
    {
        $id_account = $request->input('id_account');
        $id_employee = $request->input('id_employee');
        $username = $request->input('username');
        $password = $request->input('password');
        $status = $request->input('status');
        $permission = $request->input('permission');
        $auto_pwd = $request->input('auto_pwd');

        if($auto_pwd == 'true')
            $password = $this->randomUserPwd(20);
        $hashPass = password_hash($password, PASSWORD_BCRYPT);
        $i = [
            'id_employee' => $id_employee,
            'username' => $username,
            'email' => null,
            'password' => $hashPass,
            'status' => $status,
            'permission' => $permission,
        ];
        if(AccountModel::where('id_account',$id_account)->update($i)){
            return $this->status('Cập nhật thành công', 200);
        };
        return $this->status('Cập nhật thất bại', 500);
    }

    function delete(Request $request)
    {
        $id_account = $request->input('id_account');
        if(AccountModel::where('id_account',$id_account)->delete()){
            return $this->status('Xóa tài khoản thành công', 200);
        };
        return $this->status('Xóa thất bại', 500);
    }

    function setLastActive(Request $request)
    {
        $id_account = $request->input('id_account');
        $i = [
            'last_active' => Carbon::now()
        ];
        if(AccountModel::where('id_account',$id_account)->update($i)){
            return $this->status('Cập nhật thành công', 200);
        };
        return $this->status('Cập nhật thất bại', 500);
    }

    static function timeAgo($timestamp) {
        $timeDifference = time() - strtotime($timestamp);

        if ($timeDifference < 1) {
            return 'Just now';
        }

        $condition = array(
            12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $timeDifference / $secs;

            if ($d >= 1) {
                $t = round($d);
                return 'Active ' . $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
            }
        }
    }
}
