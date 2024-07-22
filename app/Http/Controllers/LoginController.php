<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\SinhVienModel;
use App\StaticString;
use App\SVStaticString;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function getViewLogin()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $account = AccountModel::where([
            'username' => $request->username,
            'password' =>  $request->password,
        ])->first();

        if ($account) {
            $request->session()->put(StaticString::SESSION_ISLOGIN, true);
            $request->session()->put(StaticString::ACCOUNT_ID, $account['id_account']);
            return redirect()->action('App\Http\Controllers\DashboardController@getViewDashboard');
        }

        return redirect()
            ->action('App\Http\Controllers\LoginController@postLogin')
            ->with('msg', 'Username or password is incorrect');
    }

    public function logOut(Request $request){
        $request->session()->forget(StaticString::SESSION_ISLOGIN);
        return redirect()->action('App\Http\Controllers\LoginController@getViewLogin');
    }
}
