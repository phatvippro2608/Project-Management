<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\EmployeeModel;
use App\Models\SpreadsheetModel;
use App\StaticString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    private $status = [1 => 'Active', 2 => 'Offine', 3 => 'Locked'];
    private $permission = [1 => 'Super Admin', 2 => 'Admin', 0 => 'User'];

    function getView(Request $request)
    {
        $perPage = intval(env('ITEM_PER_PAGE'));
        $keyword = $request->input('keyw', '');

        $account = EmployeeModel::query()
            ->join('account', 'account.id_employee', '=', 'employees.id_employee')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('last_name', 'like', "%{$keyword}%")
                    ->orWhere('first_name', 'like', "%{$keyword}%")
                    ->orWhere('username', 'like', "%{$keyword}%")
                    ->orWhere('employee_code', 'like', "%{$keyword}%");
            })
            ->paginate($perPage);

//        $account = AccountModel::getAll();
        $sql = "SELECT * from employees";
        $employees = DB::select($sql);
        $status = $this->status;
        return view('auth.account.account', ['account' => $account, 'employees' => $employees, 'status' => $this->status, 'permission' => $this->permission]);
    }

    static function permission()
    {
        return Session::get(StaticString::PERMISSION);
    }

    function position(Request $request)
    {
        $request->session()->put(StaticString::POSITION, $request->input('9EqClX7gzeiZAQ2wtsghJxIfR3irIM375lq8LPTRS2A7sG9tvcRmyVTor00PiYBE'));
    }

    function getPosition(Request $request)
    {
        return $request->session()->get(StaticString::POSITION);
    }

    function randomUserPwd($max = 5)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $max; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

    static function toAttrJson($data, $list = [])
    {
        if (count($list)) {
            $tmp = array();
            $data = (array)$data;
            foreach ($list as $key) {
                $tmp[$key] = $data[$key];
            }
            return json_encode($tmp);
        }
        return json_encode($data);
    }

    static function format($date)
    {
        $date_str = new \DateTime($date);
        return $date_str->format('d-m-Y');
    }

    static function getNow()
    {
        $date_str = new \DateTime();
        return $date_str->format('F j, Y');
    }

    public static function status($message, $code)
    {
        return json_encode((object)["status" => $code, "message" => $message]);
    }

    function add(Request $request)
    {
        $id_employee = $request->input('id_employee', '');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $status = $request->input('status');
        $permission = $request->input('permission');

        if ($permission == 1) {
            if (AccountModel::where('permission', 1)->where('id_employee', '!=', $id_employee)->count() >= 3) {
                return $this->status('Đã quá số lượng Super Admin', 500);
            };
        }

        if (AccountModel::where('id_employee', $id_employee)->count() >= 1) {
            return $this->status('Tài khoản đã tồn tại', 500);
        };

        if ($id_employee == -1) {
            return $this->status('Vui lòng chọn nhân viên cần tạo tài khoản', 500);
        }

        if (AccountModel::where('email', $email)->count() >= 1) {
            return $this->status('Email đã tồn tại', 500);
        }

        if (AccountModel::where('username', $username)->count() >= 1) {
            return $this->status('Username đã tồn tại', 500);
        }

        $hashPass = password_hash($password, PASSWORD_BCRYPT);
        $i = [
            'id_employee' => $id_employee,
            'username' => $username,
            'email' => $email,
            'password' => $hashPass,
            'status' => $status,
            'permission' => $permission,
        ];
        if (AccountModel::insert($i)) {
            return $this->status('Thêm thành công', 200);
        };
        return $this->status('Thêm thất bại', 500);
    }

    function update(Request $request)
    {
        $id_account = $request->input('id_account');
        $id_employee = $request->input('id_employee');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $status = $request->input('status');
        $permission = $request->input('permission');

        if (AccountModel::where('id_employee', $id_employee)->where('id_account', '!=', $id_account)->count() >= 1) {
            return $this->status('Tài khoản đã tồn tại', 500);
        };
//        $auto_pwd = $request->input('auto_pwd');
//
//        if($auto_pwd == 'true')
//            $password = $this->randomUserPwd(20);
        $hashPass = password_hash($password, PASSWORD_BCRYPT);

        if ($permission == 1) {
            if (AccountModel::where('permission', 1)->where('id_employee', '!=', $id_employee)->count() >= 3) {
                return $this->status('Đã quá số lượng Super Admin', 500);
            };
        }

        $i = [
            'id_employee' => $id_employee,
            'username' => $username,
            'email' => $email,
            'permission' => $permission,
            'status' => $status,
        ];
        if (!empty($password))
            $i['password'] = $hashPass;
        if (AccountModel::where('id_account', $id_account)->update($i)) {
            return $this->status('Cập nhật thành công', 200);
        };
        return $this->status('Cập nhật thất bại', 500);
    }

    function delete(Request $request)
    {
        $id_account = $request->input('id_account');
        if (AccountModel::where('id_account', $id_account)->delete()) {
            return $this->status('Xóa tài khoản thành công', 200);
        };
        return $this->status('Xóa thất bại', 500);
    }

    function demoView()
    {
        return view('auth.account.account_import_demo');
    }

    function import(Request $request)
    {
        $dataExcel = SpreadsheetModel::readExcel($request->file('file-excel'));
        $c = true;
        foreach ($dataExcel['data'] as $item) {
            if ($c) {
                $c = false;
                continue;
            };
            $data = [
                'email' => trim($item[0]),
                'ho_ten' => trim($item[1]),
            ];
            DB::table('account_import')->insert($data);
        }
        return $this->status('Import thành công', 200);
    }


    function loginHistory(Request $request)
    {
        $keyword = $request->input('keyw', '');
        $id_account = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $permission = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::PERMISSION);

        if($permission == 1 || $permission == 2){
            if ($keyword != null) {
                $history = DB::table('login_history')->whereDate('created_at', $keyword)->orderBy('created_at', 'desc')->get();
            } else
                $history = DB::table('login_history')->orderBy('created_at', 'desc')->get();
        }else{
            $sql_get_id_employee = "SELECT * FROM employees, account WHERE employees.id_employee = account.id_employee AND id_account = $id_account";
            $employee = DB::selectOne($sql_get_id_employee);
            if ($keyword != null) {
                $history = DB::table('login_history')->where(['username' => $employee->username])->whereDate('created_at', $keyword)->orderBy('created_at', 'desc')->get();
            } else
                $history = DB::table('login_history')->where(['username' => $employee->username])->orderBy('created_at', 'desc')->get();
        }

        return view('auth.account.account-history', ['history' => $history]);
    }

    function clearHistory()
    {
        try {
            DB::table('login_history')->truncate();
            return self::status('All history has been deleted', 200);
        } catch (\Exception $exception) {
            return self::status('Failed to delete history', 500);
        }

    }
}
