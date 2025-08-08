<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Evaluation;
use App\Models\EvaluationGridType;
use App\Models\EvaluationScore;
use App\Models\EvaluationScoreStudent;
use App\Models\GridType;
use App\Models\LogHistory;
use App\Models\ScoreSubColumn;
use App\Models\ScoreTable;
use App\Models\Subject;
use App\Models\SubjectGrid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    // Return subjects by class
    public function getSubjects($classId)
    {
        $class = Classe::with('subjects')->findOrFail($classId);
        return response()->json($class->subjects);
    }


    // Return evaluations by class and subject
    public function getEvaluations($classId, $subjectId)
    {
        $grids = SubjectGrid::where('subject_id', $subjectId)->get();
        return response()->json($grids);
    }
    public function index()
    {
        $evaluations = Evaluation::with(['class', 'subject'])->paginate(15);
        return view('feature.evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $classes = Classe::with('generation')->get();
        return view('feature.evaluations.add', compact('classes'));
    }

    public function store(Request $request)
    {
        // Step 0: Check if this evaluation already exists
        $exists = DB::table('evaluation_grid_types')
            ->join('evaluations', 'evaluation_grid_types.evaluation_id', '=', 'evaluations.id')
            ->where('evaluation_grid_types.class_id', $request->class_id)
            ->where('evaluation_grid_types.subject_grid_id', $request->subject_grid_id)
            ->where('evaluations.subject_id', $request->subject_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors([
                'duplicate' => 'This evaluation already exists for the selected class and subject'
            ]);
        }

        // Step 1: Create the Evaluation
        $evaluation = Evaluation::create([
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
        ]);

        // Step 2: Create Evaluation Scores
        $names = $request->input('evaluation_names', []);
        $percentages = $request->input('evaluation_points', []);

        $scoreIds = [];

        foreach ($names as $index => $name) {
            $percentage = $percentages[$index] ?? 0;

            if (!empty($name)) {
                $score = $evaluation->scores()->create([
                    'evaluation_name' => $name,
                    'point' => $percentage,
                ]);

                $scoreIds[] = $score->id;
            }
        }

        // Step 3: Get matching grid_types (based on class_id and subject_grid_id)
        $gridTypes = DB::table('grid_types')
            ->where('class_id', $request->class_id)
            ->where('subject_grid_id', $request->subject_grid_id)
            ->get();

        foreach ($gridTypes as $grid) {
            // Step 4: Create evaluation_grid_type
            $evaluationGridTypeId = DB::table('evaluation_grid_types')->insertGetId([
                'evaluation_id'    => $evaluation->id,
                'student_id'       => $grid->student_id,
                'grid_type_id'     => $grid->id,
                'subject_grid_id'  => $grid->subject_grid_id,
                'class_id'         => $grid->class_id,
                'total'            => 0,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);

            // Step 5: For each evaluation_score, create evaluation_score_student
            foreach ($scoreIds as $scoreId) {
                DB::table('evaluation_score_students')->insert([
                    'evaluation_grid_type_id' => $evaluationGridTypeId,
                    'evaluation_score_id'     => $scoreId,
                    'score'                   => 0,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]);
            }
        }

        DB::table('grid_types')
            ->where('class_id', $request->class_id)
            ->where('subject_grid_id', $request->subject_grid_id)
            ->update(['has_evaluation' => true]);

        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description' => 'Evaluation [ ' . ucwords(implode(', ', $names)) . ' ] was created on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',

        ]);
        $logHistory->save();
        return redirect()->route('evaluations.index')->with('success', 'Evaluation created successfully.');
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['class', 'subject', 'scores']);
        return view('feature.evaluations.show', compact('evaluation'));
    }

    public function edit($id)
    {
        $evaluation = Evaluation::with('gridTypes')->findOrFail($id);
        $classes = Classe::all();
        $subjects = Subject::all();
        $subjectGrids = SubjectGrid::all();

        // Extract existing subject_grid_id from related EvaluationGridTypes
        $selectedSubjectGrids = $evaluation->gridTypes->pluck('subject_grid_id')->toArray();

        return view('feature.evaluations.edit', compact(
            'evaluation',
            'classes',
            'subjects',
            'subjectGrids',
            'selectedSubjectGrids'
        ));
    }

    public function destroy(Evaluation $evaluation)
    {
        // Get related evaluation_grid_types
        $relatedGridTypes = \App\Models\EvaluationGridType::where('evaluation_id', $evaluation->id)->get();

        // Loop through each related row and update grid_types
        foreach ($relatedGridTypes as $item) {
            \App\Models\GridType::where([
                'student_id' => $item->student_id,
                'subject_grid_id' => $item->subject_grid_id,
                'id' => $item->grid_type_id,
                'class_id' => $item->class_id,
            ])->update([
                'has_evaluation' => false,
                'total_evaluation' => 0,
            ]);
        }
        // Get score names before deleting the evaluation
        $scoreNames = $evaluation->scores()->pluck('evaluation_name')->toArray();


        // Delete related evaluation_grid_types first (if needed)
        \App\Models\EvaluationGridType::where('evaluation_id', $evaluation->id)->delete();

        // Delete the evaluation
        $evaluation->delete();

        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Evaluation [ ' . ucwords(implode(', ', $scoreNames)) . ' ] was deleted on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();
        return redirect()->route('evaluations.index')->with('success', 'Evaluation and related grid_types updated and deleted.');
    }




    public function enterScores($evaluationId)
    {
        $evaluation = Evaluation::with(['class', 'subject'])->findOrFail($evaluationId);

        $scoreTypes = EvaluationScore::where('evaluation_id', $evaluationId)->get(); // e.g. Exercise1, Exercise2

        $evaluationGridTypes = EvaluationGridType::with('student')
            ->where('evaluation_id', $evaluationId)
            ->get();

        $scores = EvaluationScoreStudent::all();
        // dd($scores);

        return view('feature.evaluations.scores', compact('evaluation', 'scoreTypes', 'evaluationGridTypes', 'scores'));
    }

    public function saveScores(Request $request, $evaluationId)
    {
        // dd(request()->all());

        $data = $request->input('scores'); // 2D array: [evaluation_grid_type_id][evaluation_score_id] => score
        $hasDetail = $request->input('has_detail', []);
        // Step 1: Load score definitions
        $scoreTypes = EvaluationScore::where('evaluation_id', $evaluationId)->get()->keyBy('id');

        $errors = [];

        // Step 2: Loop through submitted scores and validate
        foreach ($data as $gridTypeId => $scoreItems) {
            foreach ($scoreItems as $scoreId => $value) {
                $hasDetailValue = array_key_exists($scoreId, $hasDetail) ? true : false;

                EvaluationScoreStudent::where('evaluation_grid_type_id', $gridTypeId)
                    ->where('evaluation_score_id', $scoreId)
                    ->update([
                        'has_detail_evaluation' => $hasDetailValue,
                    ]);
                $scoreType = $scoreTypes[$scoreId] ?? null;

                if (!$scoreType) {
                    $errors[] = "Invalid score type.";
                    continue;
                }

                if ($value > $scoreType->point) {
                    $errors[] = "Score for \"{$scoreType->evaluation_name}\" cannot exceed {$scoreType->point} points.";
                }

                if ($value < 0) {
                    $errors[] = "Score for \"{$scoreType->evaluation_name}\" cannot be negative.";
                }
            }
        }

        // Step 3: Redirect back with errors
        if (count($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // Step 4: Save if no validation errors
        foreach ($data as $gridTypeId => $scoreItems) {
            $total = 0;

            foreach ($scoreItems as $scoreId => $value) {
                EvaluationScoreStudent::updateOrCreate(
                    [
                        'evaluation_grid_type_id' => $gridTypeId,
                        'evaluation_score_id' => $scoreId
                    ],
                    [
                        'score' => $value
                    ]
                );

                $total += $value;
            }

            EvaluationGridType::where('id', $gridTypeId)->update(['total' => $total]);

            $evaluationGridType = EvaluationGridType::find($gridTypeId);

            if ($evaluationGridType) {
                GridType::where('class_id', $evaluationGridType->class_id)
                    ->where('subject_grid_id', $evaluationGridType->subject_grid_id)
                    ->where('student_id', $evaluationGridType->student_id)
                    ->where('id', $evaluationGridType->grid_type_id)
                    ->update([
                        'total_evaluation' => $total,
                        'has_evaluation' => true,
                    ]);
            }
        }

        return redirect()->route('evaluations.scores', $evaluationId)
            ->with('success', 'Scores updated successfully.');
    }



    public function scoreTypeDetail($evaluationId, $scoreTypeId)
    {
        $evaluation = Evaluation::with(['class', 'subject'])->findOrFail($evaluationId);
        $scoreType = EvaluationScore::findOrFail($scoreTypeId);

        // Load student list from EvaluationGridType
        $evaluationGridTypes = EvaluationGridType::with('student')
            ->where('evaluation_id', $evaluationId)
            ->get();

        // Load scores with sub-columns
        $scores = EvaluationScoreStudent::with(['scoreTables.subColumns'])
            ->where('evaluation_score_id', $scoreTypeId)
            ->get()
            ->keyBy('evaluation_grid_type_id'); // group by grid_type_id for quick lookup

        return view('feature.evaluations.detail', compact('evaluation', 'scoreType', 'evaluationGridTypes', 'scores'));
    }

    public function saveDetailedScores(Request $request)
    {
        // dd(request()->all());
        $evaluationScoreStudentIds = $request->input('evaluation_score_student_id', []);
        $scoreDetails = $request->input('score_details', []);

        foreach ($evaluationScoreStudentIds as $evaluationScoreStudentId) {
            $evaluationScoreStudent = EvaluationScoreStudent::find($evaluationScoreStudentId);

            if (!$evaluationScoreStudent) {
                continue;
            }

            // Get all existing ScoreTables for this student
            $existingTables = ScoreTable::where('evaluation_score_student_id', $evaluationScoreStudentId)->get();

            // Get all names from the incoming request
            $newNames = collect($scoreDetails)->pluck('name')->toArray();

            // Remove old tables that are not in the new request
            foreach ($existingTables as $table) {
                if (!in_array($table->name, $newNames)) {
                    $table->subColumns()->delete(); // delete related sub columns first
                    $table->delete();
                }
            }

            foreach ($scoreDetails as $scoreDetail) {
                // Create or get the score table
                $scoreTable = ScoreTable::firstOrCreate(
                    [
                        'evaluation_score_student_id' => $evaluationScoreStudentId,
                        'name' => $scoreDetail['name'],
                    ]
                );

                $submittedStudentIds = collect($scoreDetail['scores'])->pluck('student_id')->toArray();

                // Delete old sub columns that are not in the new request
                ScoreSubColumn::where('score_table_id', $scoreTable->id)
                    ->whereNotIn('student_id', $submittedStudentIds)
                    ->delete();

                // Create or update sub columns
                foreach ($scoreDetail['scores'] as $score) {
                    ScoreSubColumn::updateOrCreate(
                        [
                            'score_table_id' => $scoreTable->id,
                            'student_id' => $score['student_id'],
                        ],
                        [
                            'set_score' => $score['set_score'],
                        ]
                    );
                }
            }

            // Sum all set_scores for this evaluation_score_student_id
            $total = ScoreSubColumn::whereIn('score_table_id', function ($query) use ($evaluationScoreStudentId) {
                $query->select('id')
                    ->from('score_tables')
                    ->where('evaluation_score_student_id', $evaluationScoreStudentId);
            })->sum('set_score');

            // ✅ Update the correct column
            $evaluationScoreStudent->score = $total;
            $evaluationScoreStudent->save();
            
            // === ALSO UPDATE total of EvaluationGridType and GridType ===
            $gridTypeId = $evaluationScoreStudent->evaluation_grid_type_id;

            if ($gridTypeId) {
                // Sum all EvaluationScoreStudent for this gridType
                $totalScore = EvaluationScoreStudent::where('evaluation_grid_type_id', $gridTypeId)->sum('score');

                // Update EvaluationGridType
                EvaluationGridType::where('id', $gridTypeId)->update(['total' => $totalScore]);

                // Update GridType if it exists
                $evalGrid = EvaluationGridType::find($gridTypeId);

                if ($evalGrid) {
                    GridType::where('id', $evalGrid->grid_type_id)
                        ->where('student_id', $evalGrid->student_id)
                        ->where('subject_grid_id', $evalGrid->subject_grid_id)
                        ->update([
                            'total_evaluation' => $totalScore,
                            'has_evaluation' => true,
                        ]);
                }
            }
        }



        return redirect()->back()->with('success', 'Scores saved successfully.');
    }
}
