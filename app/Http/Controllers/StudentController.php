<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Generation;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $query = Student::query();
        $students = $query->paginate(10);
        return view('feature.students.index', compact('students'));
    }

    public function create()
    {
        
        
    }

    public function store(Request $request)
    {
        
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        
    }

    public function update(Request $request, Student $student)
    {
        
    }

    public function destroy(Student $student)
    {
        
    }
}
