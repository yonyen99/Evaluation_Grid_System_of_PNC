<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\dashboard\GenerationController;
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

    // your crud .................route
    });

    // Generation route
     Route::group(['prefix' => 'generation' ], function(){
        Route::get('/',[GenerationController::class, 'index'])->name('generation');
        Route::get('/add',[GenerationController::class, 'create'])->name('add');

    });
});
