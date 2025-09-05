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
    public function index()
    {
        $user = Auth::user();
        $student_id = $user->student_id;

        $subjectsQuery = DB::table('grid_types as gt')
            ->join('subject_grids as sg', 'gt.subject_grid_id', '=', 'sg.id')
            ->join('subjects as sub', 'sg.subject_id', '=', 'sub.id')
            ->join('classes as c', 'gt.class_id', '=', 'c.id')
            ->join('terms as t', 'c.term_id', '=', 't.id')
            ->join('generations as g', 'c.generation_id', '=', 'g.id')
            ->where('gt.student_id', $student_id)
            ->select(
                'sub.id as subject_id',
                'sub.name as subject_name',
                'sg.grid_name as grid_name',
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
        $termTotals = [];

        foreach ($subjectsQuery as $subject) {
            $score = $subject->has_evaluation ? $subject->total_evaluation : $subject->value;
            $status = $score >= 50 ? 'Passed' : 'Failed';

            $performance[$subject->term_name][$subject->class_name][] = [
                'subject_name' => $subject->subject_name,
                'grid_name'    => $subject->grid_name,
                'score'        => $score,
                'status'       => $status,
                'needs_retake' => $score < 50
            ];

            // Calculate term totals
            $termTotals[$subject->term_name]['total_score'] = ($termTotals[$subject->term_name]['total_score'] ?? 0) + $score;
            $termTotals[$subject->term_name]['subject_count'] = ($termTotals[$subject->term_name]['subject_count'] ?? 0) + 1;
            $termTotals[$subject->term_name]['percentage'] = round($termTotals[$subject->term_name]['total_score'] / $termTotals[$subject->term_name]['subject_count'], 2);
        }

        return view('feature.report.student.index', compact('performance', 'termTotals'));
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
