<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\TestController;
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
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function(){
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
});

Route::group(['middleware'=>'auth'], function(){
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

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
});
