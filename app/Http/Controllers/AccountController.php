<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\EmployeeModel;
use App\Models\SpreadsheetModel;
use App\StaticString;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Date;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    private $status = [1 => 'Active', 2 => 'Offine', 3 => 'Locked'];
    private $permission = [1 => 'Super Admin', 2 => '', 0 => 'User'];

    function getView(Request $request)
    {
        $perPage = (int)env('ITEM_PER_PAGE');
        $keyword = $request->input('keyw', '');

        $account = AccountModel::getAll($keyword);
        $employees = EmployeeModel::all();

        $keyword = trim($keyword);
        $keyword = $this->removeVietnameseAccents($keyword);
        $account = AccountModel::getAll($keyword);

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
        $request->session()->put(StaticString::POSITION, $request->input('position'));
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
            $n = random_int(0, $alphaLength);
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
        return (new \DateTime($date))->format('d-m-Y');
    }

    static function getNow()
    {
        return (new \DateTime())->format('F j, Y');
    }

    public static function status($message, $code)
    {
        return json_encode((object)["status" => $code, "message" => $message]);
    }

    function add(Request $request)
    {
        $employee_id = $request->input('employee_id', '');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $status = $request->input('status');
        $permission = $request->input('permission');

        if ($permission == 1) {
            if (AccountModel::where('permission', 1)->where('employee_id', '!=', $employee_id)->count() >= 3) {
                return self::status('Exceeded the number of super admins', 500);
            };
        }

        if (AccountModel::where('employee_id', $employee_id)->count() >= 1) {
            return self::status('Existed account', 500);
        };

        if ($employee_id == -1) {
            return self::status('Please select a employee', 500);
        }

        if (AccountModel::where('email', $email)->count() >= 1) {
            return self::status('Email đã tồn tại', 500);
        }

        if (AccountModel::where('username', $username)->count() >= 1) {
            return self::status('Existed Username', 500);
        }

        $hashPass = password_hash($password, PASSWORD_BCRYPT);
        $i = [
            'employee_id' => $employee_id,
            'username' => $username,
            'email' => $email,
            'password' => $hashPass,
            'status' => $status,
            'permission' => $permission,
        ];
        if (AccountModel::insert($i)) {
            return self::status('Added account', 200);
        };
        return self::status('Failed to add account', 500);
    }

    function update(Request $request)
    {
        $account_id = $request->input('account_id');
        $employee_id = $request->input('employee_id');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $status = $request->input('status');
        $permission = $request->input('permission');

        if (AccountModel::where('employee_id', $employee_id)->where('account_id', '!=', $account_id)->count() >= 1) {
            return self::status('Existed Account', 500);
        };
//        $auto_pwd = $request->input('auto_pwd');
//
//        if($auto_pwd == 'true')
//            $password = $this->randomUserPwd(20);
        $hashPass = password_hash($password, PASSWORD_BCRYPT);

        if ($permission == 1) {
            if (AccountModel::where('permission', 1)->where('employee_id', '!=', $employee_id)->count() >= 3) {
                return self::status('Exceeded the number of super admins', 500);
            };
        }

        $i = [
            'employee_id' => $employee_id,
            'username' => $username,
            'email' => $email,
            'permission' => $permission,
            'status' => $status,
        ];
        if (!empty($password))
            $i['password'] = $hashPass;
        if (AccountModel::where('account_id', $account_id)->update($i)) {
            return self::status('Updated account', 200);
        };
        return self::status('Failed to update', 500);
    }

    function delete(Request $request)
    {
        $account_id = $request->input('account_id');
        if (AccountModel::where('account_id', $account_id)->delete()) {
            return self::status('Deleted account', 200);
        };
        return self::status('Failed to delete account', 500);
    }
    function demoView()
    {
        return view('auth.accounts.account_import_demo');
    }

    function removeVietnameseAccents($str) {
        $accents_arr = [
            'a' => ['à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ'],
            'e' => ['è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ'],
            'i' => ['ì', 'í', 'ị', 'ỉ', 'ĩ'],
            'o' => ['ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ'],
            'u' => ['ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ'],
            'y' => ['ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ'],
            'd' => ['đ'],
            'A' => ['À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ'],
            'E' => ['È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ'],
            'I' => ['Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ'],
            'O' => ['Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ'],
            'U' => ['Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ'],
            'Y' => ['Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ'],
            'D' => ['Đ']
        ];

        foreach ($accents_arr as $non_accent => $accents) {
            $str = str_replace($accents, $non_accent, $str);
        }

        return $str;
    }

    function import(Request $request)
    {
        $dataExcel = SpreadsheetModel::readExcel($request->file('file-excel'));
        $c = true;
        foreach ($dataExcel['data'] as $item) {
            if ($c) {
                $c = false;
                continue;
            }
            $data = [
                'email' => trim($item[0]),
                'ho_ten' => trim($item[1]),
            ];
            DB::table('account_import')->insert($data);
        }
        return self::status('Import thành công', 200);
    }


    function loginHistory(Request $request)
    {
        $keyword = $request->input('keyw', '');
        $account_id = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::ACCOUNT_ID);
        $permission = \Illuminate\Support\Facades\Request::session()->get(\App\StaticString::PERMISSION);

        if($permission == 1){
            if ($keyword != null) {
                $history = DB::table('login_history')->whereDate('created_at', $keyword)->orderBy('created_at', 'desc')->get();
            } else
                $history = DB::table('login_history')->orderBy('created_at', 'desc')->get();
        }else{
            $sql_get_employee_id = "SELECT * FROM employees, accounts WHERE employees.employee_id = accounts.employee_id AND account_id = $account_id";
            $employee = DB::selectOne($sql_get_employee_id);
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
