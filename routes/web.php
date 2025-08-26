<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\dashboard\adminReportController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\GenerationController;
use App\Http\Controllers\Dashboard\SubjectController;
use App\Http\Controllers\dashboard\TeacherController;
use App\Http\Controllers\Dashboard\TestController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Dashboard\ClassController;
use App\Http\Controllers\Dashboard\LogHistoryController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\dashboard\studentReportController;
use App\Http\Controllers\dashboard\teacherReportController;
use App\Http\Controllers\Dashboard\TermController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EvaluationScoreStudentController;
use App\Http\Controllers\GridTypeController;
use GuzzleHttp\Middleware;

// Login Routes (Accessible without authentication)
Route::get('/login', [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');

// Routes requiring authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Log History Router [BEGIN]
    Route::group([
        'prefix' => 'loghistories',
    ], function () {
        Route::get('/', [LogHistoryController::class, 'index'])->name('logHistory-list');
    });
    // Log History Router [END]

    // System Users Router [BEGIN]
    Route::group([
        'prefix' => 'users',
    ], function () {
        Route::get('/', [UserController::class, 'index'])->name('user-list')->middleware('permission:view system_user');
        Route::get('/create', [UserController::class, 'create'])->name('user-add')->middleware('permission:create system_user');
        Route::post('/create', [UserController::class, 'store'])->middleware(['permission:create system_user',]);
        Route::get('{id}/edit', [UserController::class, 'edit'])->middleware('permission:edit system_user');
        Route::patch('{id}/edit', [UserController::class, 'update'])->name('user-update')->middleware(['permission:edit system_user']);
        Route::get('{id}/detail', [UserController::class, 'show'])->name('user-detail');
        Route::delete('{id}', [UserController::class, 'destroy'])->name('user-delete')->middleware('permission:delete system_user');
    });
    // System Users Router [END]

    // Roles & Permissions Router [BEGIN]
    Route::group([
        'prefix' => 'roles',
    ], function () {
        Route::get('/', [RoleController::class, 'index'])->name('role-list')->middleware('permission:view role');
        Route::get('/create', [RoleController::class, 'create'])->name('role-add')->middleware('permission:create role');
        Route::post('/create', [RoleController::class, 'store'])->middleware('permission:create role');
        Route::get('{id}/edit', [RoleController::class, 'edit'])->middleware('permission:edit role');
        Route::patch('{id}/edit', [RoleController::class, 'update'])->name('role-update')->Middleware('permission:edit role');
        Route::delete('{id}', [RoleController::class, 'destroy'])->name('role-delete')->middleware('permission:delete role');
    });
    // Roles & Permissions Router [END]

    // Generation route
    Route::group(['prefix' => 'generation'], function () {
        Route::get('/', [GenerationController::class, 'index'])->name('generation');
        Route::get('/add', [GenerationController::class, 'create'])->name('generation-add'); // Show form create
        Route::post('/create', [GenerationController::class, 'store'])->name('generation-create'); // input data form to database
        Route::get('{id}/edit', [GenerationController::class, 'edit'])->name('generation-edit'); // show form update
        Route::patch('{id}/edit', [GenerationController::class, 'update'])->name('generation-update'); // update data to database
        Route::delete('{id}', [GenerationController::class, 'destroy'])->name('generation-delete'); // delete data
        Route::get('{id}/downloadCsv', [GenerationController::class, 'generationExport'])->name('downloadCsv');
        Route::post('/import', [GenerationController::class, 'generationImport'])->name('importCsvGeneration');
    });

    // student 
    Route::group(['prefix' => 'student'], function () {
        Route::get('/', [StudentController::class, 'index'])->name('student');
        Route::get('/add', [StudentController::class, 'create'])->name('student-add');
        Route::post('/create', [StudentController::class, 'store'])->name('student-create');
        Route::get('{id}/edit', [StudentController::class, 'edit'])->name('student-edit');
        Route::patch('{id}/edit', [StudentController::class, 'update'])->name('student-update');
        Route::delete('{id}', [StudentController::class, 'destroy'])->name('student-delete');
        Route::get('/import', [StudentController::class, 'importform'])->name('importForm');
        Route::post('/import', [StudentController::class, 'studentImport'])->name('importCsvStudent');
    });

    // Subject 
    Route::group(['prefix' => 'subject'], function () {
        Route::get('/', [SubjectController::class, 'index'])->name('subject');
        Route::get('/add', [SubjectController::class, 'create'])->name('subject-add');
        Route::post('/create', [SubjectController::class, 'store'])->name('subject-create');
        Route::get('{id}/edit', [SubjectController::class, 'edit'])->name('subject-edit');
        Route::patch('{id}/edit', [SubjectController::class, 'update'])->name('subject-update');
        Route::delete('{id}', [SubjectController::class, 'destroy'])->name('subject-delete');
        // your crud .................route
    });

    Route::group(['prefix' => 'teacher'], function () {
        Route::get('/', [TeacherController::class, 'index'])->name('teacher');
        Route::get('/add', [TeacherController::class, 'create'])->name('teacher-add');
        Route::post('/create', [TeacherController::class, 'store'])->name('teacher-create');
        Route::get('{id}/edit', [TeacherController::class, 'edit'])->name('teacher-edit');
        Route::patch('{id}/edit', [TeacherController::class, 'update'])->name('teacher-update');
        Route::delete('{id}', [TeacherController::class, 'destroy'])->name('teacher-delete');
        // your crud .................route
    });

    // Class 
    Route::group(['prefix' => 'class'], function () {
        Route::get('/', [ClassController::class, 'index'])->name('class');
        Route::get('/add', [ClassController::class, 'create'])->name('class-add');
        Route::post('/create', [ClassController::class, 'store'])->name('class-create');
        Route::get('/{id}/students', [ClassController::class, 'assignStudentForm'])->name('class-student-form');
        Route::post('/{id}/students', [ClassController::class, 'storeAssignedStudents'])->name('class-student-store');
    });

    Route::group(['prefix' => 'term'], function () {
        Route::get('/', [TermController::class, 'index'])->name('term.index');         // List all terms grouped by generation
        Route::post('/{term}/add-class', [TermController::class, 'storeClass'])->name('term.class.store');
    });

    Route::get('/grid-types', [GridTypeController::class, 'latest'])->name('grid-types.latest');
    Route::get('/grid-types/class/{class}', [GridTypeController::class, 'index'])->name('grid-types.index');
    Route::post('/grid-types/update-score', [GridTypeController::class, 'updateScore'])->name('grid-types.update-score');
    Route::get('/grid-types/{classId}/export', [GridTypeController::class, 'gridTypeExport'])->name('grid-types.export');

    Route::prefix('evaluations')->group(function () {
        // Show list of evaluations
        Route::get('/', [EvaluationController::class, 'index'])->name('evaluations.index');

        // Show form to create new evaluation
        Route::get('/create', [EvaluationController::class, 'create'])->name('evaluations.create');

        // Store new evaluation
        Route::post('/', [EvaluationController::class, 'store'])->name('evaluations.store');

        // Show single evaluation details (with scores)
        Route::get('/{evaluation}', [EvaluationController::class, 'show'])->name('evaluations.show');

        // Show form to edit evaluation
        Route::get('/{evaluation}/edit', [EvaluationController::class, 'edit'])->name('evaluations.edit');

        // Update evaluation
        Route::put('/{evaluation}', [EvaluationController::class, 'update'])->name('evaluations.update');

        // Delete evaluation
        Route::delete('/{evaluation}', [EvaluationController::class, 'destroy'])->name('evaluations.destroy');
    });

    Route::get('/evaluations/{evaluation}/scores', [EvaluationController::class, 'enterScores'])->name('evaluations.scores');
    Route::post('/evaluations/{evaluation}/scores', [EvaluationController::class, 'saveScores'])->name('evaluations.scores.save');

    // web.php
    Route::get('/get-terms/{generationId}', [ClassController::class, 'getTermsByGeneration']);
    Route::get('/class/{id}/edit', [ClassController::class, 'edit'])->name('class-edit');
    Route::put('/class/{id}', [ClassController::class, 'update'])->name('class-update');
    Route::delete('/classes/{id}', [ClassController::class, 'destroy'])->name('classes.destroy');

    Route::get('/evaluations/{evaluation}/scores/{scoreType}/detail', [EvaluationController::class, 'scoreTypeDetail'])->name('evaluations.scoreType.detail');
    Route::post('/evaluations/{evaluation}/scores/save-details', [EvaluationController::class, 'saveDetailedScores'])->name('evaluations.scores.saveDetails');

    // Reoport Route ..
    Route::group(['prefix' => 'report'], function () {
        // Component routes of report
            Route::get('/terms/{generation_id}', [adminReportController::class, 'showTermsBasedonGeneration']);
            Route::get('/class/{terms_id}', [adminReportController::class, 'showClassBasedOnTerm']);
            Route::get('/download-subject-report', [adminReportController::class, 'downloadSubjectReport'])->name('download.subject.report');
 
        // List all terms grouped by admin
        Route::get('/admin', [adminReportController::class, 'index'])->name('admin-report'); 
        // your route ...................        
        
        // List all terms grouped by teacher
        Route::get('/teacher', [teacherReportController::class, 'index'])->name('teacher-report');  
        
        // your route ...................       
        
        // List all terms grouped by student
        Route::get('/student', [studentReportController::class, 'index'])->name('student-report');  

        // your route ...................   
        
        
    });
});
