<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\ClasseStudent;
use App\Models\ClassSubjectTeacher;
use App\Models\Generation;
use App\Models\GridType;
use App\Models\LogHistory;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SubjectGrid;
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
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('generation_id')) {
            $query->where('generation_id', $request->generation_id);
        }

        $classes = $query->with('generation')
                        ->orderBy('name')
                        ->paginate(10) // ✅ pagination
                        ->appends($request->query());

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

        // Update basic class info
        $class->update([
            'name' => $request->name,
            'generation_id' => $request->generation_id,
            'term_id' => $request->term_id,
        ]);

        // Remove old ClassSubjectTeacher entries
        ClassSubjectTeacher::where('class_id', $class->id)->delete();

        // Insert new ClassSubjectTeacher entries
        foreach ($request->subjects as $index => $subject_id) {
            ClassSubjectTeacher::create([
                'class_id' => $class->id,
                'subject_id' => $subject_id,
                'teacher_id' => $request->teachers[$index],
            ]);
        }

        // --- New code start: sync grid_types for all assigned students and all subjects ---

        // Get all subjects assigned to this class (after update)
        $subjectIds = $request->subjects;

        // Get all students assigned to this class
        $assignedStudents = $class->students()->pluck('students.id');

        foreach ($assignedStudents as $studentId) {
            // Get the classe_student pivot record id
            $classeStudent = DB::table('classe_students')
                ->where('class_id', $class->id)
                ->where('student_id', $studentId)
                ->first();

            if (!$classeStudent) {
                continue; // Just safety check
            }

            $classeStudentId = $classeStudent->id;

            foreach ($subjectIds as $subjectId) {
                // Get all grids of this subject
                $subjectGrids = DB::table('subject_grids')
                    ->where('subject_id', $subjectId)
                    ->get();

                foreach ($subjectGrids as $grid) {
                    // Check if grid_type exists
                    $exists = DB::table('grid_types')
                        ->where('student_id', $studentId)
                        ->where('subject_grid_id', $grid->id)
                        ->where('classe_student_id', $classeStudentId)
                        ->exists();

                    if (!$exists) {
                        // Insert missing grid_type
                        DB::table('grid_types')->insert([
                            'student_id' => $studentId,
                            'subject_grid_id' => $grid->id,
                            'classe_student_id' => $classeStudentId,
                            'value' => 0,
                            'class_id' => $class->id,
                            'subject_id' => $subjectId,
                            'has_evaluation' => false,
                            'total_evaluation' => 0,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // --- New code end ---

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

        $termId = $class->term_id;

        // Get IDs of students assigned to any other class in the same term (exclude current class)
        $assignedStudentIds = DB::table('classe_students')
            ->join('classes', 'classe_students.class_id', '=', 'classes.id')
            ->where('classes.term_id', $termId)
            ->where('classe_students.class_id', '!=', $class->id)
            ->pluck('student_id')
            ->toArray();

        // Get students who belong to the same generation AND are NOT assigned to other classes in same term
        $students = Student::where('generation_id', $class->generation_id)
            ->whereNotIn('id', $assignedStudentIds)
            ->get();

        return view('feature.class.assign-students', compact('class', 'students'));
    }

    public function storeAssignedStudents(Request $request, $id)
    {
        $class = Classe::findOrFail($id);

        $request->validate([
            'students' => 'nullable|array',
            'students.*' => 'exists:students,id',
        ]);

        $selectedStudentIds = $request->students ?? [];

        // Get the current term ID of this class
        $termId = $class->term_id;

        // Check if any selected student is already assigned to another class in the same term
        $conflictedStudents = DB::table('classe_students')
            ->join('classes', 'classe_students.class_id', '=', 'classes.id')
            ->whereIn('classe_students.student_id', $selectedStudentIds)
            ->where('classes.term_id', $termId)
            ->where('classe_students.class_id', '!=', $class->id) // exclude current class
            ->select('classe_students.student_id')
            ->distinct()
            ->pluck('student_id');

        if ($conflictedStudents->isNotEmpty()) {
            // Get the names of conflicted students
            $names = Student::whereIn('id', $conflictedStudents)->pluck('first_name', 'id')->map(function ($name, $id) {
                return $name;
            })->implode(', ');

            return back()->withErrors(['students' => "Some students are already assigned to another class in the same term: $names."]);
        }

        // Sync the selected students
        $class->students()->sync($selectedStudentIds);

        // Add grid_types as before
        $subjectIds = DB::table('class_subject_teachers')
            ->where('class_id', $class->id)
            ->pluck('subject_id');

        foreach ($selectedStudentIds as $studentId) {
            $classeStudent = DB::table('classe_students')
                ->where('class_id', $class->id)
                ->where('student_id', $studentId)
                ->first();

            if ($classeStudent) {
                $classeStudentId = $classeStudent->id;

                foreach ($subjectIds as $subjectId) {
                    $subjectGrids = DB::table('subject_grids')
                        ->where('subject_id', $subjectId)
                        ->get();

                    foreach ($subjectGrids as $grid) {
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
