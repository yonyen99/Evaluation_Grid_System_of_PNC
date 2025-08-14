<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Generation;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class adminReportController extends Controller
{
   /**
    * Show admin report form.
    */
   public function index(Request $request){
      $generations = Generation::all();
      $adminType   = $request['type'];
      switch ($adminType) {
         case 'subject':
            $generation = $request['geneation'];
            $terms      = $request['terms'];
            $action     = $request->input('action');

            if($action !== 'submit'){
               return view('feature.report.admin.pdf');

            }else{
               return view('feature.report.admin.detail');
            }
         break;
         case 'class':
            $generation = $request['geneation'];
            $terms      = $request['terms'];
            $action     = $request->input('action');
            if($action !== 'submit'){
               return view('feature.report.admin.pdf');

            }else{
               return view('feature.report.admin.detail');
            }
         break;

         default:
            return view('feature.report.admin.index', compact('generations'));
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
