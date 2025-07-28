<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\Evaluation;
use App\Models\EvaluationGridType;
use App\Models\EvaluationScore;
use App\Models\EvaluationScoreStudent;
use App\Models\GridType;
use App\Models\LogHistory;
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


    // public function update(Request $request, Evaluation $evaluation)
    // {
    //     $request->validate([
    //         'class_id' => 'required|exists:classes,id',
    //         'subject_id' => 'required|exists:subjects,id',
    //         'evaluation_names' => 'required|array',
    //         'evaluation_point' => 'required|array',
    //         // Optional validation for subject grids, if used
    //         'subject_grid_ids' => 'nullable|array',
    //         'subject_grid_ids.*' => 'exists:subject_grids,id',
    //         // Optional: validation for score IDs if editing existing
    //         'score_ids' => 'nullable|array',
    //     ]);

    //     // 1. Update main evaluation info
    //     $evaluation->update([
    //         'class_id' => $request->class_id,
    //         'subject_id' => $request->subject_id,
    //     ]);

    //     // 2. Sync subject grids ONLY if provided (to avoid deleting on empty)
    //     if ($request->has('subject_grid_ids')) {
    //         $evaluation->subjectGrids()->sync($request->subject_grid_ids);
    //     }

    //     // 3. Update evaluation scores carefully
    //     $existingScoreIds = $evaluation->scores()->pluck('id')->toArray();
    //     $submittedScoreIds = $request->input('score_ids', []);

    //     $names = $request->input('evaluation_names');
    //     $percentages = $request->input('evaluation_point');

    //     // Update existing scores and add new ones
    //     foreach ($names as $index => $name) {
    //         $percentage = $percentages[$index] ?? 0;
    //         $scoreId = $submittedScoreIds[$index] ?? null;

    //         if ($scoreId && in_array($scoreId, $existingScoreIds)) {
    //             // Update existing score
    //             $score = $evaluation->scores()->find($scoreId);
    //             if ($score) {
    //                 $score->update([
    //                     'evaluation_name' => $name,
    //                     'percentage' => $percentage,
    //                 ]);
    //             }
    //         } else {
    //             // Create new score
    //             $evaluation->scores()->create([
    //                 'evaluation_name' => $name,
    //                 'percentage' => $percentage,
    //             ]);
    //         }
    //     }

    //     // 4. Delete scores that were removed in the form (if any)
    //     $scoresToDelete = array_diff($existingScoreIds, $submittedScoreIds);
    //     if (!empty($scoresToDelete)) {
    //         $evaluation->scores()->whereIn('id', $scoresToDelete)->delete();
    //     }

    //     // 5. (Optional) You may want to update related evaluation_grid_types and evaluation_score_students here
    //     // based on your business logic, but be careful to NOT delete them unintentionally.

    //     return redirect()->route('evaluations.index')->with('success', 'Evaluation updated successfully.');
    // }



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
        $data = $request->input('scores'); // 2D array: [evaluation_grid_type_id][evaluation_score_id] => score

        // Step 1: Load score definitions
        $scoreTypes = EvaluationScore::where('evaluation_id', $evaluationId)->get()->keyBy('id');

        $errors = [];

        // Step 2: Loop through submitted scores and validate
        foreach ($data as $gridTypeId => $scoreItems) {
            foreach ($scoreItems as $scoreId => $value) {
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
}
