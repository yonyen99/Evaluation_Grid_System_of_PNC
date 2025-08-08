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

    public function gridTypeExport($classId)
    {
        $class = Classe::with('generation')->findOrFail($classId);

        $classeStudents = ClasseStudent::with('student')
            ->where('class_id', $classId)
            ->get();

        $subjectIds = $class->subjectTeachers()->pluck('subject_id')->unique();
        $subjects = Subject::with('subjectGrids')
            ->whereIn('id', $subjectIds)
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=grid_type_class_{$classId}.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($classeStudents, $subjects, $class) {
            $handle = fopen('php://output', 'w');

            foreach ($subjects as $subject) {
                $subjectGrids = $subject->subjectGrids;

                // === Subject Title Row ===
                fputcsv($handle, []);
                fputcsv($handle, [$subject->name]); // e.g., "Web Design"

                // === Header Row: Grid Names + Percentages ===
                $headerRow = ['First Name', 'Last Name'];
                foreach ($subjectGrids as $grid) {
                    $headerRow[] = $grid->grid_name . ' (' . $grid->percentage . '%)';
                }
                $headerRow[] = 'Total';
                fputcsv($handle, $headerRow);

                // === Student Rows ===
                foreach ($classeStudents as $cs) {
                    $row = [
                        $cs->student->first_name,
                        $cs->student->last_name,
                    ];

                    $total = 0;

                    foreach ($subjectGrids as $grid) {
                        $value = GridType::where('classe_student_id', $cs->id)
                            ->where('subject_grid_id', $grid->id)
                            ->first();

                        $hasEvaluation = $value->has_evaluation ?? false;
                        $scoreValue = $hasEvaluation
                            ? $value->total_evaluation ?? 0
                            : $value->value ?? 0;

                        $weighted = ($scoreValue * $grid->percentage) / 100;
                        $total += $weighted;

                        $row[] = $scoreValue;
                    }

                    $row[] = round($total, 2);
                    fputcsv($handle, $row);
                }

                // === Extra Empty Row for Separation ===
                fputcsv($handle, []);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
