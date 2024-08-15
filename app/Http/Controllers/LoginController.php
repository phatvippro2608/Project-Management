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
use function Laravel\Prompts\table;

class LoginController extends Controller
{
    public function getViewLogin()
    {
        return view('auth.login');
    }

    public function postLogin(Request $request)
    {
        $sql = "SELECT * FROM accounts WHERE username = '$request->username' or email = '$request->username' LIMIT 1";
        $account = DB::select($sql);
        if (!$account) {
            DB::table('login_history')->insert(['status'=>0,'desc'=>'fail', 'username'=>$request->username, 'ip'=>\Illuminate\Support\Facades\Request::session()->get(\App\StaticString::POSITION)]);
            return redirect()
                ->action('App\Http\Controllers\LoginController@postLogin')
                ->with('msg', 'Username or password is incorrect');
        }

        if (password_verify($request->password, $account[0]->password) && $account[0]->status!==3) {
            $request->session()->put(StaticString::SESSION_ISLOGIN, true);
            $request->session()->put(StaticString::PERMISSION, $account[0]->permission);
            $request->session()->put(StaticString::ACCOUNT_ID, $account[0]->account_id);
            DB::table('login_history')->insert(['status'=>1,'desc'=>'success', 'username'=>$request->username, 'ip'=>\Illuminate\Support\Facades\Request::session()->get(\App\StaticString::POSITION)]);
            return redirect()->action('App\Http\Controllers\DashboardController@getViewDashboard');
        }else{
            return redirect()
                ->action('App\Http\Controllers\LoginController@postLogin')
                ->with('msg', 'Account is locked');
        }

        DB::table('login_history')->insert(['status'=>0,'desc'=>'fail', 'username'=>$request->username, 'ip'=>\Illuminate\Support\Facades\Request::session()->get(\App\StaticString::POSITION)]);
        return redirect()
            ->action('App\Http\Controllers\LoginController@postLogin')
            ->with('msg', 'Username or password is incorrect');
    }


    public function logOut(Request $request){
        $request->session()->flush();
        return redirect()->action('App\Http\Controllers\LoginController@getViewLogin');
    }
}
