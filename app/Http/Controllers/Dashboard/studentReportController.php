<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Generation;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class studentReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student_id = $user->student_id;
        $generations = Generation::all();
        $generation_id = $request->input('generation');
        $term_id       = $request->input('term');
        $class_id      = $request->input('class');

        $student_id = Auth::user()->student_id;

        // === Get the student's classes/subjects for the selected filters ===
        $subjectsQuery = DB::table('grid_types as gt')
            ->join('subject_grids as sg', 'gt.subject_grid_id', '=', 'sg.id')
            ->join('subjects as sub', 'sg.subject_id', '=', 'sub.id')
            ->join('classes as c', 'gt.class_id', '=', 'c.id')
            ->join('terms as t', 'c.term_id', '=', 't.id')
            ->join('generations as g', 'c.generation_id', '=', 'g.id')
            ->where('gt.student_id', $student_id)
            ->when($generation_id, fn($q) => $q->where('g.id', $generation_id))
            ->when($term_id, fn($q) => $q->where('t.id', $term_id))
            ->when($class_id, fn($q) => $q->where('c.id', $class_id))
            ->select(
                'sub.id as subject_id',
                'sub.name as subject_name',
                'c.id as class_id',
                'c.name as class_name',
                't.id as term_id',
                't.name as term_name',
                'g.id as generation_id',
                'g.name as generation_name',
                'gt.value',
                'gt.total_evaluation',
                'gt.has_evaluation'
            )
            ->get();

        $performance = [];

        foreach ($subjectsQuery as $subject) {
            $score = $subject->has_evaluation ? $subject->total_evaluation : $subject->value;
            $status = $score >= 50 ? 'Passed' : 'Failed'; // example pass mark 50

            $performance[$subject->term_name][$subject->class_name][] = [
                'subject_name' => $subject->subject_name,
                'score'        => $score,
                'status'       => $status,
                'needs_retake' => $score < 50 ? true : false
            ];
        }

        // === Filters for dropdowns ===
        $generations = Generation::all();
        $terms       = $generation_id ? Term::where('generation_id', $generation_id)->get() : collect();
        $classes     = ($generation_id && $term_id) ? Classe::where('generation_id', $generation_id)->where('term_id', $term_id)->get() : collect();

        return view('feature.report.student.index', compact(
            'performance',
            'generations',
            'terms',
            'classes',
            'generation_id',
            'term_id',
            'class_id',
        ));

        return view('feature.report.student.index', compact('generations'));
    }
    

    /**
     * Show admin report form.
     */
    public function showTermsBasedonGeneration($id)
    {

        $terms = Term::where('generation_id', $id)->get();
        return response()->json($terms);
    }

    /**
     * Show admin report form.
     */
    public function showClassBasedOnTerm($id)
    {
        $class = Classe::where('term_id', $id)->get();
        return response()->json($class);
    }
}
