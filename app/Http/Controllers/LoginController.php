<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\SinhVienModel;
use App\StaticString;
use App\SVStaticString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use function Laravel\Prompts\select;

class LoginController extends Controller
{
    public function getViewLogin()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $sql = "SELECT * FROM account WHERE username = '$request->username' or email = '$request->username' LIMIT 1";
        $account = DB::select($sql);
        if (!$account) {
            return redirect()
                ->action('App\Http\Controllers\LoginController@postLogin')
                ->with('msg', 'Username or password is incorrect');
        }

        if (password_verify($request->password, $account[0]->password)) {
            $request->session()->put(StaticString::SESSION_ISLOGIN, true);
            $request->session()->put(StaticString::PERMISSION, $account[0]->permission);
            $request->session()->put(StaticString::ACCOUNT_ID, $account[0]->id_account);
            return redirect()->action('App\Http\Controllers\DashboardController@getViewDashboard');
        }

        return redirect()
            ->action('App\Http\Controllers\LoginController@postLogin')
            ->with('msg', 'Username or password is incorrect');
    }


    public function logOut(Request $request){
        $request->session()->flush();
        return redirect()->action('App\Http\Controllers\LoginController@getViewLogin');
    }
}
