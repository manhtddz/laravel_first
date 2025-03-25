<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TeamController;
use App\Http\Middleware\AuthenticationMiddleware;
use App\Http\Middleware\ClearSessionTempFileMiddleware;
use App\Http\Middleware\ClearTempFileMiddleware;
use App\Http\Middleware\TimeoutMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('auth.admin')->middleware(
);
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
//emp crud route
Route::middleware([
    AuthenticationMiddleware::class,
    ClearTempFileMiddleware::class,
    TimeoutMiddleware::class
])->prefix("team")->group(function () {
    Route::post('update/{id}', [TeamController::class, 'update'])->name('team.update');
    Route::post('create', [TeamController::class, 'create'])->name('team.create');
    Route::post('delete/{id}', [TeamController::class, 'delete'])->name('team.delete');
});
//team crud route
Route::middleware([
    AuthenticationMiddleware::class,
    ClearTempFileMiddleware::class,
    TimeoutMiddleware::class
])->prefix("employee")->group(function () {
    Route::post('update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::post('create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('delete/{id}', [EmployeeController::class, 'delete'])->name('employee.delete');
});

//team get template
Route::middleware([
    AuthenticationMiddleware::class,
    ClearSessionTempFileMiddleware::class
])->prefix("team")->group(function () {

    Route::get('', [TeamController::class, 'index'])->name('team.index');

    Route::get('edit/{id}', [TeamController::class, 'edit'])->name('team.edit');
    Route::post('updateConfirm/{id}', [TeamController::class, 'updateConfirm'])->name('team.updateConfirm');
    Route::get('updateConfirm/{id}', [TeamController::class, 'showUpdateConfirm'])->name('team.updateConfirm');

    Route::get('create', [TeamController::class, 'getCreateForm'])->name('team.create');
    Route::post('createConfirm', [TeamController::class, 'createConfirm'])->name('team.createConfirm');
    Route::get('createConfirm', [TeamController::class, 'showCreateConfirm'])->name('team.showCreateConfirm');
});

//employee get template
Route::middleware([
    AuthenticationMiddleware::class,
    ClearSessionTempFileMiddleware::class
])->prefix("employee")->group(function () {

    Route::get('', [EmployeeController::class, 'index'])->name('employee.index');

    Route::post('updateConfirm/{id}', [EmployeeController::class, 'updateConfirm'])->name('employee.updateConfirm');
    Route::get('updateConfirm/{id}', [EmployeeController::class, 'showUpdateConfirm'])->name('employee.updateConfirm');

    Route::post('createConfirm', [EmployeeController::class, 'createConfirm'])->name('employee.createConfirm');
    Route::get('createConfirm', [EmployeeController::class, 'showCreateConfirm'])->name('employee.showCreateConfirm');
    Route::post('export', [EmployeeController::class, 'export'])->name('employee.export');
});

//employee get create and update template
Route::middleware([
    AuthenticationMiddleware::class,
])->prefix("employee")->group(function () {
    Route::get('edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::get('create', [EmployeeController::class, 'getCreateForm'])->name('employee.create');
});