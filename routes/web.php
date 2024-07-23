<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.dashboard.dashboard');
});

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

Route::get('/progress', 'App\Http\Controllers\ProgressController@getViewProgress');

Route::get('/employees', 'App\Http\Controllers\EmployeesController@getView');

Route::get('progress', 'App\Http\Controllers\ProjectsController@getViewProgress');

Route::get('expenses', 'App\Http\Controllers\ProjectsController@getViewExpenses');
