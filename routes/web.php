<?php

use App\StaticString;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

use App\Http\Controllers\MaterialsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProgressController;

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
    Route::get('/', function (){
        return redirect('/dashboard');
    });
    Route::get('/dashboard', 'App\Http\Controllers\DashboardController@getViewDashboard')->name('home');

    Route::group(['prefix'=>'/account', 'middleware' => 'isSuperAdmin'], function() {
        Route::get('/', 'App\Http\Controllers\AccountController@getView');
        Route::put('/add', 'App\Http\Controllers\AccountController@add');
        Route::post('/update', 'App\Http\Controllers\AccountController@update');
        Route::delete('/delete', 'App\Http\Controllers\AccountController@delete');

        Route::get('/demo', function (){
            return view('auth.account.account_import_demo');
        });
        Route::get('/demo', 'App\Http\Controllers\AccountController@demoView');
        Route::post('/demo', 'App\Http\Controllers\AccountController@import');
    });

    Route::group(['prefix'=>'/team', 'middleware' => 'isSuperAdmin'], function() {
        Route::get('/team', 'App\Http\Controllers\TeamController@getView');
    });



    Route::group(['prefix'=>'/employees', 'middleware' => 'isAdmin'], function() {
        Route::get('/', 'App\Http\Controllers\EmployeesController@getView');
//        Route::post('/putEmployee', 'App\Http\Controllers\EmployeesController@put');
        Route::put('/postEmployee', 'App\Http\Controllers\EmployeesController@put');
        Route::delete('/deleteEmployee', 'App\Http\Controllers\EmployeesController@delete');
        Route::get('/info/{id_employee}', 'App\Http\Controllers\EmployeesController@getEmployee');
    });


    Route::group(['prefix'=>'/profile', 'middleware' => 'isAdmin'], function() {
        Route::get('/', 'App\Http\Controllers\ProfileController@getViewProfile');
        Route::post('/update', 'App\Http\Controllers\ProfileController@postProfile');
    });

    Route::post('/upload', 'App\Http\Controllers\UploadFileController@uploadFile');
    Route::post('/upload_photo', 'App\Http\Controllers\UploadFileController@uploadPhoto');
    Route::post('/upload_personal_profile', 'App\Http\Controllers\UploadFileController@uploadPersonalProfile');
    Route::post('/upload_medical_checkup', 'App\Http\Controllers\UploadFileController@uploadMedicalCheckUp');
    Route::post('/upload_certificate', 'App\Http\Controllers\UploadFileController@uploadCertificate');

});

Route::get('/employees', 'App\Http\Controllers\EmployeesController@getView');
Route::get('/project-list', [\App\Http\Controllers\ProjectListController::class, 'getView'])->name('project.list');
Route::get('/materials', [MaterialsController::class, 'getView'])->name('materials.index');
Route::post('/materials', [MaterialsController::class, 'store'])->name('materials.store');
Route::get('materials/{id}/edit', [MaterialsController::class, 'edit'])->name('materials.edit');
Route::put('materials/{id}', [MaterialsController::class, 'update'])->name('materials.update');
Route::delete('materials/{id}', [MaterialsController::class, 'destroy'])->name('materials.destroy');


Route::get('/progress', 'App\Http\Controllers\ProgressController@getView');
Route::post('/update-item', [ProgressController::class, 'updateItem']);
Route::post('/progress', [TaskController::class, 'create'])->name('task.create');
Route::get('/task/task/{id}', [TaskController::class, 'showTask'])->name('task.getTasksData');
Route::get('/task/subtask/{id}', [TaskController::class, 'showSubTask'])->name('task.getSubTasksData');
Route::post('/task/update', [TaskController::class, 'update'])->name('task.update');

Route::get('/task', [TaskController::class, 'getView'])->name('task.index');
Route::get('/phase/{phase}', [TaskController::class, 'showPhaseTasks'])->name('phase.tasks');
Route::get('/task/{task}', [TaskController::class, 'showTaskSubtasks'])->name('task.subtasks');


Route::get('/project/{id}', [\App\Http\Controllers\ProjectBudgetController::class, 'showProjectDetail'])->name('project.details');
Route::get('/project/{id}/budget', [\App\Http\Controllers\ProjectBudgetController::class, 'getView'])->name('budget');

Route::get('/project/{id}/budget/edit', [\App\Http\Controllers\ProjectBudgetController::class, 'editBudget'])->name('budget.edit');
Route::get('/project/{id}/budget/edit/{costGroupId}', [\App\Http\Controllers\ProjectBudgetController::class, 'getBudgetData'])->name('budget.data');


//Inventory Management
Route::get('inventory', [\App\Http\Controllers\InventoryManagementController::class, 'getView'])->name('inventory');