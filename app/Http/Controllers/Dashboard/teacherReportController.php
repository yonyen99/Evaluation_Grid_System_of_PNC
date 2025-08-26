<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Generation;
use App\Models\GridType;
use App\Models\SubjectGrid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class teacherReportController extends Controller
{
    public function index(Request $request)
    {
        $teacherType = $request->input('type');
        $generations = Generation::all();

        $user = Auth::user();
        $teacher_id = $user->teacher_id;

        switch ($teacherType) {
            case 'teaching_assigment':
                $generation_id = $request->input('generation');
                $action        = $request->input('action');

                if ($action == 'submit') {
                    // ✅ Fetch subjects & classes taught by this teacher
                    $reports = DB::table('class_subject_teachers as cst')
                        ->join('classes as c', 'cst.class_id', '=', 'c.id')
                        ->join('terms as t', 'c.term_id', '=', 't.id')
                        ->join('generations as g', 'c.generation_id', '=', 'g.id')
                        ->join('subjects as s', 'cst.subject_id', '=', 's.id')
                        ->where('cst.teacher_id', $teacher_id)
                        ->when($generation_id, fn($q) => $q->where('g.id', $generation_id))
                        ->when($request->input('term'), fn($q, $term_id) => $q->where('t.id', $term_id))
                        ->select(
                            'g.name as generation',
                            't.name as term',
                            'c.name as class_name',
                            's.name as subject'
                        )
                        ->orderBy('g.id')
                        ->orderBy('t.id')
                        ->orderBy('c.name')
                        ->get();
                    return view('feature.report.teacher.detail', compact('reports', 'generations'));
                }
                break;

            case 'student_performance':
                $generation_id = $request->input('generation');
                $term_id       = $request->input('term');
                $class_id      = $request->input('class');
                $action        = $request->input('action');
                $student_id    = $request->input('student_id');

                if ($action == 'submit') {

                    // === Get students ===
                    $studentsQuery = DB::table('students as s')
                        ->join('grid_types as gt', 's.id', '=', 'gt.student_id')
                        ->join('classes as c', 'gt.class_id', '=', 'c.id')
                        ->join('terms as t', 'c.term_id', '=', 't.id')
                        ->join('generations as g', 'c.generation_id', '=', 'g.id')
                        ->select(
                            's.id as student_id',
                            's.first_name',
                            's.last_name',
                            'g.name as generation'
                        )
                        ->when($generation_id, fn($q) => $q->where('g.id', $generation_id))
                        ->when($term_id, fn($q) => $q->where('t.id', $term_id))
                        ->when($class_id, fn($q) => $q->where('c.id', $class_id));

                    if ($student_id) {
                        $studentsQuery->where('s.id', $student_id);
                    }

                    $students = $studentsQuery
                        ->groupBy('s.id', 's.first_name', 's.last_name', 'g.name')
                        ->get();

                    $scores = [];
                    $subjectsByStudent = [];

                    if ($students->count()) {

                        foreach ($students as $student) {

                            // === Get only subjects the student has scores for ===
                            $subjects = DB::table('grid_types as gt')
                                ->join('subject_grids as sg', 'gt.subject_grid_id', '=', 'sg.id')
                                ->join('subjects as sub', 'sg.subject_id', '=', 'sub.id')
                                ->join('classes as c', 'gt.class_id', '=', 'c.id')
                                ->join('terms as t', 'c.term_id', '=', 't.id')
                                ->join('generations as g', 'c.generation_id', '=', 'g.id')
                                ->where('gt.student_id', $student->student_id)
                                ->select(
                                    'sub.id',
                                    'sub.name',
                                    'c.id as class_id',
                                    'c.name as class_name',
                                    't.name as term_name',
                                    'g.name as generation_name'
                                )
                                ->distinct()
                                ->get();

                            // Group subjects by class for display
                            $subjectsByStudent[$student->student_id] = $subjects->groupBy('class_name');

                            // Calculate total scores for each subject
                            foreach ($subjects as $subject) {
                                $grids = \App\Models\SubjectGrid::where('subject_id', $subject->id)->get();

                                $total = 0;
                                foreach ($grids as $grid) {
                                    $valueRecord = \App\Models\GridType::where('student_id', $student->student_id)
                                        ->where('subject_grid_id', $grid->id)
                                        ->first();

                                    $hasEvaluation = $valueRecord->has_evaluation ?? false;
                                    $scoreValue    = $hasEvaluation
                                        ? $valueRecord->total_evaluation ?? 0
                                        : $valueRecord->value ?? 0;

                                    $total += ($scoreValue * $grid->percentage) / 100;
                                }

                                $scores[$student->student_id][$subject->id] = $total;
                            }
                        }
                    }

                    // === For filters (Term & Class dropdown) ===
                    $generations = \App\Models\Generation::all();
                    $terms       = $generation_id ? \App\Models\Term::where('generation_id', $generation_id)->get() : collect();
                    $classes     = ($generation_id && $term_id) ? \App\Models\Classe::where('generation_id', $generation_id)
                        ->where('term_id', $term_id)->get() : collect();

                    return view('feature.report.teacher.student_performance', compact(
                        'students',
                        'scores',
                        'subjectsByStudent',
                        'generations',
                        'terms',
                        'classes'
                    ));
                }
                break;




            default:
                return view('feature.report.teacher.index', compact('generations'));
        }
    }
}
