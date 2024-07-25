<?php

use App\StaticString;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\TaskController;

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

Route::group(['prefix'=>'/', 'middleware' => 'isLogin'], function() {
    Route::get('/', 'App\Http\Controllers\DashboardController@getViewDashboard')->name('home');

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

Route::get('/progress', 'App\Http\Controllers\ProgressController@getView');
Route::post('/progress', [TaskController::class, 'create'])->name('task.create');
Route::get('/task/task/{id}', [TaskController::class, 'showTask'])->name('task.getTasksData');
Route::get('/task/subtask/{id}', [TaskController::class, 'showSubTask'])->name('task.getSubTasksData');
Route::post('/task/update', [TaskController::class, 'update'])->name('task.update');

Route::get('/employees', 'App\Http\Controllers\EmployeesController@getView');
Route::get('/materials', [MaterialsController::class, 'getView'])->name('materials.index');
Route::post('/materials', [MaterialsController::class, 'store'])->name('materials.store');
Route::get('materials/{id}/edit', [MaterialsController::class, 'edit'])->name('materials.edit');
Route::put('materials/{id}', [MaterialsController::class, 'update'])->name('materials.update');
Route::delete('materials/{id}', [MaterialsController::class, 'destroy'])->name('materials.destroy');
