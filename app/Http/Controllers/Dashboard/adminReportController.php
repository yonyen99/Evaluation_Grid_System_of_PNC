<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
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
      return view('feature.report.admin.index', compact('generations'));
   }

   /**
    * Show admin report form.
    */
   public function showTermsBasedonGeneration($id){
        $terms = Term::where('generation_id', $id)->get();
        return response()->json($terms);
   }

   /**
     * Show information after submite form.
     */
   public function show(Request $request){
      return view('feature.report.admin.detail');
   }
}
