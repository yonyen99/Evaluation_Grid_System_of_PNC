<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClasseStudent;
use App\Models\ClassSubjectTeacher;
use App\Models\Subject;
use App\Models\Term;
use App\Models\Generation;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TranscriptController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $role = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $user->id)
            ->where('model_has_roles.model_type', get_class($user))
            ->select('roles.name')
            ->first();

        // dd($role->name);
        if (in_array($role->name, ['Teacher', 'admin'])) {
            // Teachers/Admins must select a student first
            $studentId = $request->input('student_id');

            if (!$studentId) {
                // Show student selection view
                $students = Student::orderBy('first_name')->get();
                return view('feature.transcript.select_student', compact('students'));
            }
        } else {
            // If student, use their own student_id
            $studentId = $user->student_id;
        }

        // Get student information
        $student = Student::find($studentId);

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found');
        }

        // Get all classes of the student
        $classeStudents = ClasseStudent::with(['classe.generation', 'classe.term'])
            ->where('student_id', $studentId)
            ->get();

        $transcript = [];
        $academicPeriod = '';

        foreach ($classeStudents as $cs) {
            $class = $cs->classe;
            $term = $class->term;
            $generation = $class->generation;

            // Set academic period from the first generation
            if (empty($academicPeriod)) {
                $academicPeriod = $generation->start_year . ' - ' . $generation->end_year;
            }

            // Get all subjects for this class
            $subjects = ClassSubjectTeacher::with(['subject.subjectGrids.gridType'])
                ->where('class_id', $class->id)
                ->get();

            $itTraining = [];
            $generalTraining = [];

            foreach ($subjects as $cst) {
                $subject = $cst->subject;
                $totalScore = 0;

                foreach ($subject->subjectGrids as $grid) {
                    $value = $grid->gridType->firstWhere('classe_student_id', $cs->id);

                    $hasEvaluation = $value->has_evaluation ?? false;
                    $scoreValue = $hasEvaluation
                        ? $value->total_evaluation ?? 0
                        : $value->value ?? 0;

                    $weighted = ($scoreValue * $grid->percentage) / 100;
                    $totalScore += $weighted;
                }

                $grade = $this->getGrade($totalScore);

                $subjectData = [
                    'name' => $subject->name,
                    'credit' => $subject->credit,
                    'score' => round($totalScore),
                    'grade' => $grade,
                ];

                // Categorize subjects by training type
                if ($this->isGeneralTraining($subject->name)) {
                    $generalTraining[] = $subjectData;
                } else {
                    $itTraining[] = $subjectData;
                }
            }

            $transcript[] = [
                'term_name' => $term->name,
                'generation' => $generation->name,
                'start_year' => $generation->start_year,
                'end_year' => $generation->end_year,
                'it_training' => $itTraining,
                'general_training' => $generalTraining,
            ];
        }

        return view('feature.transcript.index', compact('transcript', 'student', 'academicPeriod'));
    }


    private function getGrade($score)
    {
        if ($score >= 85) return 'A';
        if ($score >= 80) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'C+';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'E';
    }

    private function isGeneralTraining($subjectName)
    {
        $generalTrainingKeywords = ['English', 'Professional Life', 'Communication', 'Math'];

        foreach ($generalTrainingKeywords as $keyword) {
            if (stripos($subjectName, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }
}
