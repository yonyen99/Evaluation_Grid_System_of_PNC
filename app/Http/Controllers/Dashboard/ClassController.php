<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\ClassSubjectTeacher;
use App\Models\Generation;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $query = Classe::query();
        $classes = $query->paginate(10);
        return view('feature.class.index', compact('classes'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'generation_id' => 'required|exists:generations,id',
            'subjects' => 'required|array',
            'teachers' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
            'teachers.*' => 'exists:teachers,id',
        ]);

        $class = Classe::create([
            'name' => $request->name,
            'generation_id' => $request->generation_id,
        ]);

        foreach ($request->subjects as $index => $subject_id) {
            ClassSubjectTeacher::create([
                'class_id' => $class->id,
                'subject_id' => $subject_id,
                'teacher_id' => $request->teachers[$index],
            ]);
        }

        return redirect()->route('class')->with('success', 'Class created successfully.');
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

        return redirect()->route('class')->with('success', 'Students assigned successfully.');
    }
}
