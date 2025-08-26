<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Generation;
use App\Models\LogHistory;
use App\Models\Province;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
   public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $query->with('generation');

        $query->orderBy('first_name'); // or 'id', 'last_name', etc.

        $students = $query->paginate(10)->appends($request->query());

        $generations = Generation::all();
        $provinces = Province::all();

        return view('feature.students.index', compact('students', 'generations', 'provinces'));
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

        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Student [ ' . ucwords($student->first_name) . ' ] was created on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();

        return redirect()->route('student')->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::find($id);
        $generations = Generation::all();

        if (!$student) {
            return back()->with('error', 'Student not found.');
        }

        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Student [ ' . ucwords($student->first_name) . ' ] was updated on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();

        return view('feature.students.edit', compact('student', 'generations'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return back()->with('error', 'Student not found.');
        }

        $request->validate([
            'first_name'   => 'required|string',
            'last_name'    => 'required|string',
            'profile'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'generation_id' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('profile')) {
                $filePath = $request->file('profile')->store('profiles', 'public');
                $student->profile = $filePath;
            }

            $student->first_name    = $request->first_name;
            $student->last_name     = $request->last_name;
            $student->generation_id = $request->generation_id;
            $student->save();

            DB::commit();
            return redirect()->route('student')->with('success', 'Student updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error occurred while updating the student: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return back()->with('error', 'Student not found.');
        }

        try {
            DB::beginTransaction();

            $student->delete();

            DB::commit();

            $currentUser = auth()->user();
            $logHistory  = new LogHistory([
                'log_header'      => 'create role',
                'permission_slug' => 'view role_history',
                'username'        => $currentUser->username,
                'user_id'         => $currentUser->id,
                'description'     => 'Student [ ' . ucwords($student->first_name) . ' ] was deleted on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
            ]);
            $logHistory->save();
            return redirect()->route('student')->with('success', 'Student deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error occurred while deleting the student: ' . $e->getMessage());
        }
    }
}
