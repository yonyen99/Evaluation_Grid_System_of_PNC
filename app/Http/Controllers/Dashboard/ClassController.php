<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\ClassSubjectTeacher;
use App\Models\Generation;
use App\Models\LogHistory;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = Classe::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        $classes = $query->with('generation')->get();
        $generations = Generation::all();

        return view('feature.class.index', compact('classes', 'generations'));
    }
    public function getTermsByGeneration($generationId)
    {
        $terms = Term::where('generation_id', $generationId)->get();
        return response()->json($terms);
    }
    public function create()
    {
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $generations = Generation::all();
        return view('feature.class.add', compact('subjects', 'teachers', 'generations'));
    }

    public function store(Request $request)
    {
        // dd(request()->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'generation_id' => 'required|exists:generations,id',
            'term_id' => 'required|exists:terms,id',
            'subjects' => 'required|array',
            'teachers' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
            'teachers.*' => 'exists:teachers,id',
        ]);

        $class = Classe::create([
            'name' => $request->name,
            'generation_id' => $request->generation_id,
            'term_id' => $request->term_id, // add this line
        ]);

        foreach ($request->subjects as $index => $subject_id) {
            ClassSubjectTeacher::create([
                'class_id' => $class->id,
                'subject_id' => $subject_id,
                'teacher_id' => $request->teachers[$index],
            ]);
        }
        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Class [ ' . ucwords($class->name) . ' ] was created on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();

        return redirect()->route('class')->with('success', 'Class created successfully.');
    }

    public function edit($id)
    {
        $class = Classe::with('subjectTeachers')->findOrFail($id); // eager load subjects-teachers relationship
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $generations = Generation::all();

        // Get terms for the class's generation to populate term select
        $terms = Term::where('generation_id', $class->generation_id)->get();
        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Class [ ' . ucwords($class->name) . ' ] was updated on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();

        return view('feature.class.edit', compact('class', 'subjects', 'teachers', 'generations', 'terms'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'generation_id' => 'required|exists:generations,id',
            'term_id' => 'required|exists:terms,id',
            'subjects' => 'required|array',
            'teachers' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
            'teachers.*' => 'exists:teachers,id',
        ]);

        $class = Classe::findOrFail($id);

        $class->update([
            'name' => $request->name,
            'generation_id' => $request->generation_id,
            'term_id' => $request->term_id,
        ]);

        // Remove old ClassSubjectTeacher entries
        ClassSubjectTeacher::where('class_id', $class->id)->delete();

        // Insert new ones
        foreach ($request->subjects as $index => $subject_id) {
            ClassSubjectTeacher::create([
                'class_id' => $class->id,
                'subject_id' => $subject_id,
                'teacher_id' => $request->teachers[$index],
            ]);
        }

        return redirect()->route('class')->with('success', 'Class updated successfully.');
    }

    public function destroy($id)
    {
        $class = Classe::findOrFail($id);
        $class->subjectTeachers()->delete();

        $class->delete();
        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Class [ ' . ucwords($class->name) . ' ] was deleted on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();


        return redirect()->back()->with('success', 'Class deleted successfully!');
    }


    public function assignStudentForm($id)
    {
        $class = Classe::with('generation', 'students')->findOrFail($id);

        // Get students who belong to the same generation
        $students = Student::where('generation_id', $class->generation_id)->get();

        return view('feature.class.assign-students', compact('class', 'students'));
    }

    public function storeAssignedStudents(Request $request, $id)
    {
        $class = Classe::findOrFail($id);

        $request->validate([
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id',
        ]);

        // Sync the selected students
        $class->students()->sync($request->students ?? []);

        // Get class subjects (via class_subject_teachers)
        $subjectIds = DB::table('class_subject_teachers')
            ->where('class_id', $class->id)
            ->pluck('subject_id');

        foreach ($request->students ?? [] as $studentId) {
            // Get classe_student ID (pivot)
            $classeStudent = DB::table('classe_students')
                ->where('class_id', $class->id)
                ->where('student_id', $studentId)
                ->first();

            if ($classeStudent) {
                $classeStudentId = $classeStudent->id;

                foreach ($subjectIds as $subjectId) {
                    // Get subject grids for this subject
                    $subjectGrids = DB::table('subject_grids')
                        ->where('subject_id', $subjectId)
                        ->get();

                    foreach ($subjectGrids as $grid) {
                        // Avoid duplicates
                        $exists = DB::table('grid_types')
                            ->where('student_id', $studentId)
                            ->where('subject_grid_id', $grid->id)
                            ->where('classe_student_id', $classeStudentId)
                            ->exists();

                        if (!$exists) {
                            DB::table('grid_types')->insert([
                                'student_id' => $studentId,
                                'subject_grid_id' => $grid->id,
                                'classe_student_id' => $classeStudentId,
                                'value' => 0,
                                'class_id' => $class->id,
                                'subject_id' => $subjectId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('class')->with('success', 'Students assigned successfully.');
    }
}
