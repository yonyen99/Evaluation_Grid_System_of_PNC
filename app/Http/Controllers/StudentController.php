<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Generation;
use App\Models\LogHistory;
use App\Models\Province;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'password'      => 'required|string|min:6',
            'phone'         => 'nullable|string',
            'username'      => 'required|string',
            'gender'        => 'required|in:male,female,other',
            'email'         => 'required|email|unique:students,email',
            'province_id'   => 'required|exists:provinces,id',
            'generation_id' => 'required|exists:generations,id',
            'profile'       => 'nullable|image|max:2048', // max 2MB
        ]);
        // Handle profile image upload if exists
        $profilePath = null;
        if ($request->hasFile('profile')) {
            $profilePath = $request->file('profile')->store('profiles', 'public');
        }

        $role = Role::where('name', 'Student')->first();
        $role = $role->name;

        $generation = generation::where('id',$request->generation_id)->first();

        // Create student
        $student = Student::create([
            'student_id'    => $generation->name,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'gender'        => $request->gender,
            'email'         => $request->email,
            'province_id'   => $request->province_id,
            'username'      => $request->username,
            'phone'         => $request->phone,
            'password'     => Hash::make($request->password),
            'generation_id' => $request->generation_id,
            'profile'       => $request->profile,
        ]);
        $student->save();
        $student->student_id = $generation->name. 00 .$student->id;
        $student->update();
        $user = new User([
            'lastname'          => $request->last_name,
            'firstname'         => $request->first_name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'username'          => $request->username,
            'password'          => Hash::make($request->password),
            'profile'           => $request->profile,
            'teacher_id'        => null,
            'student_id'        => $student->id,
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'display'           => 'student',
        ]);

        $user->save();

        // attach user with role
        $user->assignRole($role);


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
        $user = User::where('student_id', $id)->first();


        try {
            DB::beginTransaction();

            $student->delete();
            $user->roles()->detach();
            $user->permissions()->detach();
            $user->delete();
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

    public function importform()
    {
        $roles = User::getRoles();
        if (!$roles->data) {
            return back()->with('error', $roles->message);
        }
        $roles = $roles->data;

        $provinces = Province::all();
        $generations = Generation::all();
        return view('feature.students.import', compact('provinces', 'generations', 'roles'));
    }

    /**
     * import csv
     */
    // public function studentImport(Request $request){
    //     $request->validate([
    //         'importCsv' => 'required|file|mimes:csv,txt',
    //     ]);

    //     $role = User::getRole($request['role']);
    //     if (!$role->data) {
    //         return back()->with('error', $role->message);
    //     }
    //     $role = $role->data;

    //     DB::beginTransaction();

    //     $file = $request->file('importCsv');
    //     $data = array_map('str_getcsv', file($file));
    //     $header = array_map('trim', $data[0]); // First row = header
    //     unset($data[0]); // Remove header


    //     foreach ($data as $row) {
    //         $rowData = array_combine($header, $row);
    //         // Example: insert into generations table
    //         $student = student::create([
    //             'student_id'    => $rowData['student_id'],
    //             'username'      => $rowData['username'],
    //             'first_name'    => $rowData['first_name'],
    //             'last_name'     => $rowData['last_name'],
    //             'gender'        => $rowData['gender'],
    //             'email'         => $rowData['email'],
    //             'province_id'   => $rowData['province'],
    //             'phone'         => $rowData['phone'],
    //             'password'      => Hash::make($rowData['password']),
    //             'generation_id' => $request['generation_id'],
    //             'profile'       => null,
    //         ]);
    //         $student->save();
    //         $user= User::create([
    //             'lastname'          => $rowData['last_name'],
    //             'firstname'         => $rowData['first_name'],
    //             'email'             => $rowData['email'],
    //             'phone'             => $rowData['phone'],
    //             'username'          => $rowData['username'],
    //             'password'          => Hash::make($rowData['password']),
    //             'profile'           => null,
    //             'teacher_id'        => null,
    //             'student_id'        => $student->id,
    //             'email_verified_at' => Carbon::now()->toDateTimeString(),
    //             'display'           => 'student',
    //         ]);
    //         $user->save();
    //         // attach user with role
    //         $user->assignRole($role);
    //     }
    //     DB::commit();
    //     return redirect()->route('student')->with('success', 'CSV imported successfully!.');
    // }


    public function studentImport(Request $request)
    {
        $request->validate([
            'importCsv' => 'required|file|mimes:csv,txt',
        ]);

        $role = Role::where('name', 'Student')->first();
        $role = $role->name;

        $generation = generation::where('id', $request['generation_id'])->first();

        DB::beginTransaction();

        $file = $request->file('importCsv');
        $data = array_map('str_getcsv', file($file));
        $header = array_map('trim', $data[0]); // First row = header
        unset($data[0]); // Remove header

        
        foreach ($data as $key=> $row) {
            
            $rowData = array_combine($header, $row);
            $province_id = Province::where('name', $rowData['province'])->value('id');
            // Example: insert into generations table
            $province_id = Province::where('name', $rowData['province'])->value('id');
            $student = student::create([
                'student_id'    => $generation->name,
                'username'      => $rowData['username'],
                'first_name'    => $rowData['first_name'],
                'last_name'     => $rowData['last_name'],
                'gender'        => $rowData['gender'],
                'email'         => $rowData['email'],
                'province_id'   => $province_id,
                'phone'         => $rowData['phone'],
                'password'      => Hash::make($rowData['password']),
                'generation_id' => $request['generation_id'],
                'profile'       => null,
            ]);
            $student->save();
            $student->student_id  = $generation->name. 00 .$student->id;
            $student->update();
            $user= User::create([
                'lastname'          => $rowData['last_name'],
                'firstname'         => $rowData['first_name'],
                'email'             => $rowData['email'],
                'phone'             => $rowData['phone'],
                'username'          => $rowData['username'],
                'password'          => Hash::make($rowData['password']),
                'profile'           => null,
                'teacher_id'        => null,
                'student_id'        => $student->id,
                'email_verified_at' => Carbon::now()->toDateTimeString(),
                'display'           => 'student',
            ]);
            $user->save();
            // attach user with role
            $user->assignRole($role);
        }
        DB::commit();
        return redirect()->route('student')->with('success', 'CSV imported successfully!.');
    }
}
