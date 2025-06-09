<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\TestController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\TermController;

// Login Routes (Accessible without authentication)
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

// Routes requiring authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::resource('students', StudentController::class);

    // your new feature
    Route::group(['prefix' => 'test' ], function(){
        Route::get('/',[TestController::class, 'index'])->name('test');
        Route::get('/add',[TestController::class, 'create'])->name('test-add');
        Route::post('/create',[TestController::class, 'store'])->name('test-create');
        Route::get('{id}/edit',[TestController::class, 'edit'])->name('test-edit');
        Route::patch('{id}/edit',[TestController::class, 'update'])->name('test-update');
        Route::delete('{id}',[TestController::class, 'destroy'])->name('test-delete');
        // your crud .................route
    });


    // Terms of Generation route
    Route::group(['prefix' => 'term'], function(){
        Route::get('/', [TermController::class, 'index'])->name('term');

    });
});
