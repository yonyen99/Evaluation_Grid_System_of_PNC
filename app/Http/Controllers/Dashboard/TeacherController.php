<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LogHistory;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index(Request $request)
    {
        $query = Teacher::query();

        // Search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        // Order + paginate + keep query params
        $teachers = $query->orderBy('first_name')
                        ->paginate(10)
                        ->appends($request->query());

        return view('feature.teacher.index', compact('teachers'));
    }


    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('feature.teacher.add');
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => 'required|email|unique:teachers,email',
            'phone'      => 'nullable|string',
            'profile'    => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password'   => 'required|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            $filePath = null;
            if ($request->hasFile('profile')) {
                $filePath = $request->file('profile')->store('profiles', 'public');
            }
            $teacher = new Teacher([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'password'  => Hash::make($request->password),
                'profile'   => $filePath,
            ]);
            $teacher->save();

            DB::commit();
            $currentUser = auth()->user();
            $logHistory  = new LogHistory([
                'log_header'      => 'create role',
                'permission_slug' => 'view role_history',
                'username'        => $currentUser->username,
                'user_id'         => $currentUser->id,
                'description'     => 'Teacher [ ' . ucwords($teacher->first_name) . ' ] was created on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
            ]);
            $logHistory->save();
            return redirect()->route('teacher')->with('success', 'Teacher created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Error occurred while creating the teacher: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit($id)
    {
        $response = Teacher::getTeacherById($id);

        if (!$response->data) {
            return back()->with('error', $response->message);
        }

        $teacher = $response->data;
        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Teacher [ ' . ucwords($teacher->first_name) . ' ] was updated on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();
        return view('feature.teacher.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(Request $request, $id)
    {
        $response = Teacher::getTeacherById($id);

        if (!$response->data) {
            return back()->with('error', $response->message);
        }

        $teacher = $response->data;

        $request->validate([
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'email'      => "required|email|unique:teachers,email,{$id}",
            'phone'      => 'nullable|string',
            'profile'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password'   => 'nullable|string|min:6',
        ]);

        try {
            DB::beginTransaction();

            $filePath = null;
            if ($request->hasFile('profile')) {
                $filePath = $request->file('profile')->store('profiles', 'public');
            }

            $teacher->first_name = $request->first_name;
            $teacher->last_name  = $request->last_name;
            $teacher->email      = $request->email;
            $teacher->phone      = $request->phone;
            $teacher->profile    = $filePath;

            if ($request->filled('password')) {
                $teacher->password = Hash::make($request->password);
            }
            $teacher->save();

            DB::commit();
            return redirect()->route('teacher.index')->with('success', 'Teacher updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Error occurred while updating the teacher: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy($id)
    {
        $response = Teacher::getTeacherById($id);

        if (!$response->data) {
            return back()->with('error', $response->message);
        }

        $teacher = $response->data;

        try {
            DB::beginTransaction();

            $teacher->delete();

            DB::commit();

            $currentUser = auth()->user();
            $logHistory  = new LogHistory([
                'log_header'      => 'create role',
                'permission_slug' => 'view role_history',
                'username'        => $currentUser->username,
                'user_id'         => $currentUser->id,
                'description'     => 'Teacher [ ' . ucwords($teacher->first_name) . ' ] was deleted on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
            ]);
            $logHistory->save();

            return redirect()->route('teacher.index')->with('success', 'Teacher deleted successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Error occurred while deleting the teacher: ' . $e->getMessage());
        }
    }
}
