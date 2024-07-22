<?php

use App\StaticString;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/login', 'App\Http\Controllers\LoginController@getViewLogin');
Route::post('/login', 'App\Http\Controllers\LoginController@postLogin');
Route::get('/logout', 'App\Http\Controllers\LoginController@logOut');

Route::get('/', function (Request $request){
    if($request->session()->exists(StaticString::SESSION_ISLOGIN))
        return redirect()->action('App\Http\Controllers\DashboardController@getViewDashboard');
    return redirect('/login');
});

Route::group(['prefix'=> '/','middleware'=>'isLogin'],function (){
    Route::get('/', 'App\Http\Controllers\DashboardController@getViewDashboard');
});


Route::group(['prefix'=>'auth', 'middleware' => 'isLogin'], function() {
    Route::group(['prefix'=>'/account', 'middleware' => 'isSuperAdmin'], function() {
        Route::get('/', 'App\Http\Controllers\AccountController@getView');
    });
    Route::group(['prefix'=>'/employees', 'middleware' => 'isAdmin'], function() {
        Route::get('/', 'App\Http\Controllers\EmployeesController@getView');
    });
    Route::group(['prefix'=>'/profile', 'middleware' => 'isAdmin'], function() {
        Route::get('/', 'App\Http\Controllers\ProfileController@getViewProfile');
    });
});


