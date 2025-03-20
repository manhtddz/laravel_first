<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TeamController;
use App\Http\Middleware\AuthenticationMiddleware;
use App\Http\Middleware\ClearSessionTempFileMiddleware;
use App\Http\Middleware\ClearTempFileMiddleware;
use App\Http\Middleware\LoginMiddleware;
use App\Http\Middleware\SingleAccountMiddleware;
use App\Http\Middleware\TimeoutMiddleware;
use App\Http\Middleware\TimeTrackMiddleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [AuthController::class, 'index'])->name('auth.admin')->middleware(
);
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::middleware([
    AuthenticationMiddleware::class,
    ClearTempFileMiddleware::class,
    TimeTrackMiddleware::class,
    TimeoutMiddleware::class
])->group(function () {
    Route::post('team/update/{id}', [TeamController::class, 'update'])->name('team.update');
    Route::post('team/create', [TeamController::class, 'create'])->name('team.create');
    Route::post('team/delete/{id}', [TeamController::class, 'delete'])->name('team.delete');

    Route::post('employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::post('employee/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('employee/delete/{id}', [EmployeeController::class, 'delete'])->name('employee.delete');

});

Route::middleware([
    AuthenticationMiddleware::class,
    ClearSessionTempFileMiddleware::class
])->group(function () {

    Route::get('team', [TeamController::class, 'index'])->name('team.index');

    Route::get('team/edit/{id}', [TeamController::class, 'edit'])->name('team.edit');
    Route::post('team/updateConfirm/{id}', [TeamController::class, 'updateConfirm'])->name('team.updateConfirm');
    Route::get('team/updateConfirm/{id}', [TeamController::class, 'showUpdateConfirm'])->name('team.updateConfirm');

    Route::get('team/create', [TeamController::class, 'getCreateForm'])->name('team.create');
    Route::post('team/createConfirm', [TeamController::class, 'createConfirm'])->name('team.createConfirm');
    Route::get('team/createConfirm', [TeamController::class, 'showCreateConfirm'])->name('team.showCreateConfirm');


    Route::get('employee', [EmployeeController::class, 'index'])->name('employee.index');

    Route::post('employee/updateConfirm/{id}', [EmployeeController::class, 'updateConfirm'])->name('employee.updateConfirm');
    Route::get('employee/updateConfirm/{id}', [EmployeeController::class, 'showUpdateConfirm'])->name('employee.updateConfirm');

    Route::post('employee/createConfirm', [EmployeeController::class, 'createConfirm'])->name('employee.createConfirm');
    Route::get('employee/createConfirm', [EmployeeController::class, 'showCreateConfirm'])->name('employee.showCreateConfirm');
    Route::post('employee/export', [EmployeeController::class, 'export'])->name('employee.export');

});
Route::middleware([
    AuthenticationMiddleware::class,
])->group(function () {
    Route::get('employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::get('employee/create', [EmployeeController::class, 'getCreateForm'])->name('employee.create');
});