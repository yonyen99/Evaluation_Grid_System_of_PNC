<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Generation;
use App\Models\LogHistory;
use App\Models\Report;
use App\Models\Term;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class adminReportController extends Controller
{
   /**
    * Show admin report form.
    */
   public function index(Request $request){
      $generations   = Generation::all();
      $adminType     = $request['type'];
      $generationId  = $request['generation'];
      $termId        = $request['term'];
      $classId       = $request['class'];
      $action        = $request->input('action');
      $currentUser   = auth()->user();
      $userName      = $currentUser->username;
      $correntDate   = Carbon::now();

      switch ($adminType) {
         case 'subject':
            $subjectDate    = Report::getSubjectReport($generationId, $termId, $classId);
            $generationName = $subjectDate->generation;
            $termName       = $subjectDate->term;
            $className      = $subjectDate->class;
            $subjects       = $subjectDate->subject;
            if($action === 'submit'){
               $logHistory  = new LogHistory([
                  'log_header'      => 'create admin_report',
                  'permission_slug' => 'view admin_report',
                  'username'        => $currentUser->username,
                  'user_id'         => $currentUser->id,
                  'description'     => 'Subject based',
               ]);
               $logHistory->save();
               return view('feature.report.admin.detail', compact('generationName','termName','className','correntDate','userName','subjects','adminType'));
            }else{

               $logHistory  = new LogHistory([
                  'log_header'      => 'create admin_report',
                  'permission_slug' => 'view admin_report',
                  'username'        => $currentUser->username,
                  'user_id'         => $currentUser->id,
                  'description'     => 'Subject based',
               ]);
               $logHistory->save();
               return view('feature.report.admin.pdf', compact('generationName','termName','className','correntDate','userName','subjects','adminType'));
            }
         break;
         case 'class':
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
