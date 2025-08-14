<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Generation;
use App\Models\Term;
use Illuminate\Http\Request;

class teacherReportController extends Controller
{
   
    public function index(Request $request)
    {
        $generations = Generation::all();
        $adminType   = $request['type'];
        switch ($adminType) {
            case 'subject':
                $generation = $request['geneation'];
                $terms      = $request['terms'];
                $action     = $request->input('action');

                if ($action !== 'submit') {
                    return view('feature.report.teacher.pdf');
                } else {
                    return view('feature.report.teacher.detail');
                }
                break;
            case 'class':
                $generation = $request['geneation'];
                $terms      = $request['terms'];
                $action     = $request->input('action');
                if ($action !== 'submit') {
                    return view('feature.report.teacher.pdf');
                } else {
                    return view('feature.report.teacher.detail');
                }
                break;

            default:
                return view('feature.report.teacher.index', compact('generations'));
                break;
        }
    }

   /**
    * Show admin report form.
    */
   public function showTermsBasedonGeneration($id){

        $terms = Term::where('generation_id', $id)->get();
        return response()->json($terms);
   }

   /**
    * Show admin report form.
    */
   public function showClassBasedOnTerm($id){
        $class = Classe::where('term_id', $id)->get();
        return response()->json($class);
   }
}
