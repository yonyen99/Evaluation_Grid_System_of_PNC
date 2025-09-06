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
use Illuminate\Support\Facades\DB;

class adminReportController extends Controller
{
   /**
    * Show admin report form.
    */
   public function index(Request $request)
   {
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
            if ($action === 'submit') {
               $logHistory  = new LogHistory([
                  'log_header'      => 'create admin_report',
                  'permission_slug' => 'view admin_report',
                  'username'        => $currentUser->username,
                  'user_id'         => $currentUser->id,
                  'description'     => 'Subject based',
               ]);
               $logHistory->save();
               return view('feature.report.admin.detail', compact('generationName', 'termName', 'className', 'correntDate', 'userName', 'subjects', 'adminType'));
            } else {

               $logHistory  = new LogHistory([
                  'log_header'      => 'create admin_report',
                  'permission_slug' => 'view admin_report',
                  'username'        => $currentUser->username,
                  'user_id'         => $currentUser->id,
                  'description'     => 'Subject based',
               ]);
               $logHistory->save();
               return view('feature.report.admin.pdf', compact('generationName', 'termName', 'className', 'correntDate', 'userName', 'subjects', 'adminType'));
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

         case 'score_management':
            $generation_id = $request->input('generation');
            $term_id       = $request->input('term');
            $class_id      = $request->input('class');

            // ✅ Find students who should have scores but don’t (per subject + grid)
            $studentsWithoutScores = DB::table('classe_students as cs')
               ->join('students as s', 's.id', '=', 'cs.student_id')
               ->join('classes as c', 'c.id', '=', 'cs.class_id')
               ->join('terms as t', 't.id', '=', 'c.term_id')
               ->join('generations as g', 'g.id', '=', 'c.generation_id')
               ->join('class_subject_teachers as cst', 'cst.class_id', '=', 'c.id')
               ->join('subjects as sub', 'sub.id', '=', 'cst.subject_id')
               ->join('subject_grids as sg', 'sg.subject_id', '=', 'sub.id') // 🔹 include grids
               ->leftJoin('grid_types as gt', function ($join) {
                  $join->on('gt.student_id', '=', 's.id')
                     ->on('gt.class_id', '=', 'c.id')
                     ->on('gt.subject_id', '=', 'sub.id')
                     ->on('gt.subject_grid_id', '=', 'sg.id'); // 🔹 match with grid level
               })
               ->when($generation_id, fn($q) => $q->where('g.id', $generation_id))
               ->when($term_id, fn($q) => $q->where('t.id', $term_id))
               ->when($class_id, fn($q) => $q->where('c.id', $class_id))
               ->where(function ($q) {
                  $q->whereNull('gt.id')
                     ->orWhere('gt.value', 0);
               })
               ->select(
                  's.id as student_id',
                  DB::raw("CONCAT(s.first_name, ' ', s.last_name) as student_name"),
                  'c.name as class_name',
                  't.name as term_name',
                  'g.name as generation',
                  'sub.name as subject_name',
                  'sg.grid_name as grid_name',       // 🔹 which grid is missing
                  'sg.percentage as grid_percentage' // 🔹 optional: show weight of grid
               )
               ->orderBy('g.name')
               ->orderBy('t.name')
               ->orderBy('c.name')
               ->orderBy('student_name')
               ->orderBy('sub.name')
               ->orderBy('sg.grid_name')
               ->get();

            // ✅ Filters
            $generations = \App\Models\Generation::all();
            $terms       = $generation_id ? \App\Models\Term::where('generation_id', $generation_id)->get() : collect();
            $classes     = ($generation_id && $term_id)
               ? \App\Models\Classe::where('generation_id', $generation_id)->where('term_id', $term_id)->get()
               : collect();

            return view('feature.report.teacher.score_management', compact(
               'studentsWithoutScores',
               'generations',
               'terms',
               'classes'
            ));

            break;
         case 'retake_exams':
            $generation_id = $request->input('generation');
            $term_id       = $request->input('term');
            $class_id      = $request->input('class');
            $action        = $request->input('action');

            if ($action == 'submit') {
               // ✅ Get students
               $students = DB::table('classe_students as cs')
                  ->join('students as s', 's.id', '=', 'cs.student_id')
                  ->join('classes as c', 'c.id', '=', 'cs.class_id')
                  ->join('terms as t', 't.id', '=', 'c.term_id')
                  ->join('generations as g', 'g.id', '=', 'c.generation_id')
                  ->when($generation_id, fn($q) => $q->where('g.id', $generation_id))
                  ->when($term_id, fn($q) => $q->where('t.id', $term_id))
                  ->when($class_id, fn($q) => $q->where('c.id', $class_id))
                  ->select(
                     's.id as student_id',
                     's.first_name',
                     's.last_name',
                     'c.name as class_name',
                     't.name as term_name',
                     'g.name as generation_name',
                     'c.id as class_id'
                  )
                  ->get();

               $studentsRetake = [];

               foreach ($students as $student) {
                  $subjects = DB::table('class_subject_teachers as cst')
                     ->join('subjects as sub', 'sub.id', '=', 'cst.subject_id')
                     ->where('cst.class_id', $student->class_id)
                     ->select('sub.id as subject_id', 'sub.name as subject_name')
                     ->get();

                  foreach ($subjects as $subject) {
                     $grids = \App\Models\SubjectGrid::where('subject_id', $subject->subject_id)->get();

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

                     if ($total < 50) {
                        $studentsRetake[] = [
                           'student_id'   => $student->student_id,
                           'student_name' => $student->first_name . ' ' . $student->last_name,
                           'class_name'   => $student->class_name,
                           'term_name'    => $student->term_name,
                           'generation'   => $student->generation_name,
                           'subject_name' => $subject->subject_name,
                           'total'        => $total,
                        ];
                     }
                  }
               }

               $studentsRetake = collect($studentsRetake);

               // ✅ Filters
               $generations = \App\Models\Generation::all();
               $terms       = $generation_id ? \App\Models\Term::where('generation_id', $generation_id)->get() : collect();
               $classes     = ($generation_id && $term_id)
                  ? \App\Models\Classe::where('generation_id', $generation_id)->where('term_id', $term_id)->get()
                  : collect();

               return view('feature.report.teacher.retake_exams', compact(
                  'studentsRetake',
                  'generations',
                  'terms',
                  'classes'
               ));
            }
            break;

         case 'teachers_overview':
            $generation_id = $request->input('generation');
            $term_id       = $request->input('term');

            // 🔹 Filters
            $generations = \App\Models\Generation::all();
            $terms       = $generation_id ? \App\Models\Term::where('generation_id', $generation_id)->get() : collect();

            // 🔹 Teacher assignments per class and subject
            $teacherAssignments = DB::table('class_subject_teachers as cst')
               ->join('teachers as t', 't.id', '=', 'cst.teacher_id')
               ->join('subjects as sub', 'sub.id', '=', 'cst.subject_id')
               ->join('classes as c', 'c.id', '=', 'cst.class_id')
               ->join('terms as term', 'term.id', '=', 'c.term_id')
               ->join('generations as g', 'g.id', '=', 'c.generation_id')
               ->when($generation_id, fn($q) => $q->where('g.id', $generation_id))
               ->when($term_id, fn($q) => $q->where('term.id', $term_id))
               ->select(
                  't.id as teacher_id',
                  DB::raw("CONCAT(t.first_name, ' ', t.last_name) as teacher_name"),
                  't.email',
                  't.username',
                  'sub.name as subject_name',
                  'c.name as class_name',
                  'term.name as term_name',
                  'g.name as generation_name'
               )
               ->orderBy('g.name')
               ->orderBy('term.name')
               ->orderBy('c.name')
               ->orderBy('teacher_name')
               ->get();

            // 🔹 Total number of teachers for the selected term/generation
            $totalTeachers = $teacherAssignments->pluck('teacher_id')->unique()->count();

            // 🔹 Teachers late in submitting scores (no record in grid_types)
            $lateTeachers = DB::table('teachers as t')
               ->join('class_subject_teachers as cst', 't.id', '=', 'cst.teacher_id')
               ->join('classes as c', 'c.id', '=', 'cst.class_id')
               ->join('terms as term', 'term.id', '=', 'c.term_id')
               ->join('generations as g', 'g.id', '=', 'c.generation_id')
               ->leftJoin('grid_types as gt', function ($join) {
                  $join->on('gt.class_id', '=', 'c.id')
                     ->on('gt.subject_id', '=', 'cst.subject_id');
               })
               ->when($generation_id, fn($q) => $q->where('g.id', $generation_id))
               ->when($term_id, fn($q) => $q->where('term.id', $term_id))
               ->whereNull('gt.id')
               ->select('t.id as teacher_id', DB::raw("CONCAT(t.first_name, ' ', t.last_name) as teacher_name"), 't.email', 't.username')
               ->distinct()
               ->get();

            return view('feature.report.teacher.teachers_overview', compact(
               'teacherAssignments',
               'totalTeachers',
               'lateTeachers',
               'generations',
               'terms',
               'generation_id',
               'term_id'
            ));
            break;

         case 'subjects_overview':
            $generation_id = $request->input('generation');
            $term_id       = $request->input('term');

            // ✅ Count subjects per class/term/generation
            $subjects = DB::table('class_subject_teachers as cst')
               ->join('classes as c', 'c.id', '=', 'cst.class_id')
               ->join('terms as t', 't.id', '=', 'c.term_id')
               ->join('generations as g', 'g.id', '=', 'c.generation_id')
               ->join('subjects as sub', 'sub.id', '=', 'cst.subject_id')
               ->when($generation_id, fn($q) => $q->where('g.id', $generation_id))
               ->when($term_id, fn($q) => $q->where('t.id', $term_id))
               ->select(
                  'sub.id as subject_id',
                  'sub.name as subject_name',
                  'c.name as class_name',
                  't.name as term_name',
                  'g.name as generation_name'
               )
               ->distinct()
               ->orderBy('g.name')
               ->orderBy('t.name')
               ->orderBy('c.name')
               ->orderBy('sub.name')
               ->get();

            // ✅ Count of subjects
            $totalSubjects = $subjects->groupBy('subject_id')->count();

            // ✅ Filters
            $generations = \App\Models\Generation::all();
            $terms       = $generation_id ? \App\Models\Term::where('generation_id', $generation_id)->get() : collect();

            return view('feature.report.admin.subjects_overview', compact(
               'subjects',
               'totalSubjects',
               'generations',
               'terms',
               'generation_id',
               'term_id'
            ));
            break;

         case 'classes_overview':
            $generation_id = $request->input('generation');
            $term_id       = $request->input('term');

            // ✅ Get classes with counts
            $classes = DB::table('classes as c')
               ->join('generations as g', 'g.id', '=', 'c.generation_id')
               ->join('terms as t', 't.id', '=', 'c.term_id')
               ->when($generation_id, fn($q) => $q->where('g.id', $generation_id))
               ->when($term_id, fn($q) => $q->where('t.id', $term_id))
               ->select(
                  'c.id as class_id',
                  'c.name as class_name',
                  't.name as term_name',
                  'g.name as generation_name',
                  DB::raw('(SELECT COUNT(*) FROM classe_students cs WHERE cs.class_id = c.id) as student_count')
               )
               ->orderBy('g.name')
               ->orderBy('t.name')
               ->orderBy('c.name')
               ->get();

            // ✅ Get subjects per class
            $subjectsByClass = DB::table('class_subject_teachers as cst')
               ->join('subjects as sub', 'sub.id', '=', 'cst.subject_id')
               ->select('cst.class_id', 'sub.name as subject_name')
               ->get()
               ->groupBy('class_id');

            // ✅ Filters
            $generations = \App\Models\Generation::all();
            $terms       = $generation_id ? \App\Models\Term::where('generation_id', $generation_id)->get() : collect();

            return view('feature.report.admin.classes_overview', compact(
               'classes',
               'subjectsByClass',
               'generations',
               'terms',
               'generation_id',
               'term_id'
            ));
            break;


         default:
            return view('feature.report.admin.index', compact('generations'));
            break;
      }
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
