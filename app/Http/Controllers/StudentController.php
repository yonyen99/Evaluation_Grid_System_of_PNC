<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Generation;
use App\Models\Province;
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
        $provinces = Province::all();
        $generations = Generation::all();
        return view('feature.students.add', compact('provinces', 'generations'));
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'student_id' => 'required|string|unique:students,student_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'email' => 'required|email|unique:students,email',
            'province_id' => 'required|exists:provinces,id',
            'generation_id' => 'required|exists:generations,id',
            'profile' => 'nullable|image|max:2048', // max 2MB
        ]);

        // Handle profile image upload if exists
        $profilePath = null;
        if ($request->hasFile('profile')) {
            $profilePath = $request->file('profile')->store('profiles', 'public');
        }

        // Create student
        $student = Student::create([
            'student_id' => $validated['student_id'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'gender' => $validated['gender'],
            'email' => $validated['email'],
            'province_id' => $validated['province_id'],
            'generation_id' => $validated['generation_id'],
            'profile' => $profilePath,
        ]);

        return redirect()->route('student')->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student) {}

    public function update(Request $request, Student $student) {}

    public function destroy(Student $student) {}
}
