<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentYear = Carbon::now()->year;
        // Get total students
        $students = Student::whereYear('created_at', $currentYear)->get();
        $totalStudentCurrentYears  = count($students);
        // Get total teachers
        $teachers  = Teacher::whereYear('created_at', $currentYear)->get();
        $totalTeacherCurrentYears  = count($teachers);
        // Get total Classes 
        $classes  = Classe::whereYear('created_at', $currentYear)->get();
        $totalClassesCurrentYears  = count($classes);
        return view('index',compact('totalStudentCurrentYears','totalTeacherCurrentYears','totalClassesCurrentYears'));
    }

    /**
     * Update profile beased on user 
     * @param $corrent user id
     * @return \Illuminate\Http\Response
     */
    public function updateProfile($id){
        $user = User::find($id);
        if ($request->hasFile('profile')) {
            $filePath = $request->file('profile')->store('profile', 'public');
            $user->profile = $filePath;
        }
        $user->update();
        return view ('index');
    }
}
