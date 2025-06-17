<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\GenerationController;
use App\Http\Controllers\Dashboard\SubjectController;
use App\Http\Controllers\dashboard\TeacherController;
use App\Http\Controllers\Dashboard\TestController;
use App\Http\Controllers\StudentController;

// Login Routes (Accessible without authentication)
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

// Routes requiring authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
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

    // Generation route
    Route::group(['prefix' => 'generation'], function() {
        Route::get('/', [GenerationController::class, 'index'])->name('generation');
        Route::get('/add', [GenerationController::class, 'create'])->name('generation-add'); // Show form create
        Route::post('/create', [GenerationController::class, 'store'])->name('generation-create'); // input data form to database
        Route::get('{id}/edit', [GenerationController::class, 'edit'])->name('generation-edit'); // show form update
        Route::patch('{id}/edit', [GenerationController::class, 'update'])->name('generation-update'); // update data to database
        Route::delete('{id}', [GenerationController::class, 'destroy'])->name('generation-delete'); // delete data
    });

    // student 
    Route::group(['prefix' => 'student'], function(){
        Route::get('/',[StudentController::class, 'index'])->name('student');

    });

    // Subject 
    Route::group(['prefix' => 'subject' ], function(){
        Route::get('/',[SubjectController::class, 'index'])->name('subject');
        Route::get('/add',[SubjectController::class, 'create'])->name('subject-add');
        Route::post('/create',[SubjectController::class, 'store'])->name('subject-create');
        Route::get('{id}/edit',[SubjectController::class, 'edit'])->name('subject-edit');
        Route::patch('{id}/edit',[SubjectController::class, 'update'])->name('subject-update');
        Route::delete('{id}',[SubjectController::class, 'destroy'])->name('subject-delete');
        // your crud .................route
    });
    
    Route::group(['prefix' => 'teacher' ], function(){
        Route::get('/',[TeacherController::class, 'index'])->name('teacher');
        Route::get('/add',[TeacherController::class, 'create'])->name('teacher-add');
        Route::post('/create',[TeacherController::class, 'store'])->name('teacher-create');
        Route::get('{id}/edit',[TeacherController::class, 'edit'])->name('teacher-edit');
        Route::patch('{id}/edit',[TeacherController::class, 'update'])->name('teacher-update');
        Route::delete('{id}',[TeacherController::class, 'destroy'])->name('teacher-delete');
        // your crud .................route
    });
    
});
