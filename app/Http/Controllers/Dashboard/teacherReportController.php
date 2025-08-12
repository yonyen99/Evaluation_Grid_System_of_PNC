<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class teacherReportController extends Controller
{
    public function index(Request $request){
        return view('feature.report.teacher.index');
    }

    /**
     * Show information after submite form.
     */
   public function show(Request $request){
      return view('feature.report.teacher.detail');
   }
     
}
