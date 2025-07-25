<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\ClasseStudent;
use App\Models\GridType;
use App\Models\Subject;
use Illuminate\Http\Request;

class GridTypeController extends Controller
{
    public function latest()
    {
        $latestClass = Classe::latest('id')->first();

        if ($latestClass) {
            return redirect()->route('grid-types.index', $latestClass->id);
        } else {
            return redirect()->back()->with('error', 'No class found.');
        }
    }

    public function index($classId)
    {
        $class = Classe::with('generation')->findOrFail($classId);

        $classeStudents = ClasseStudent::with('student')
            ->where('class_id', $classId)
            ->get();

        $subjectIds = $class->subjectTeachers()->pluck('subject_id')->unique();

        $subjects = Subject::with('subjectGrids')
            ->whereIn('id', $subjectIds)
            ->get();

        return view('feature.grid_type.index', compact('class', 'classeStudents', 'subjects'));
    }

    public function updateScore(Request $request)
    {
        $validated = $request->validate([
            'classe_student_id' => 'required|exists:classe_students,id',
            'subject_grid_id' => 'required|exists:subject_grids,id',
            'value' => 'required|numeric|min:0|max:100',
        ]);

        GridType::updateOrCreate(
            [
                'classe_student_id' => $validated['classe_student_id'],
                'subject_grid_id' => $validated['subject_grid_id'],
            ],
            [
                'value' => $validated['value'],
            ]
        );

        return response()->json(['status' => 'success']);
    }
}
