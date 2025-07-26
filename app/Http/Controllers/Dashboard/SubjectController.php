<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\LogHistory;
use Illuminate\Http\Request;
use App\Models\Subject;
use Carbon\Carbon;
use App\Models\SubjectGrid;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subjects = Subject::getSubjects();
        // dd($subjects);
        if (!$subjects->data) {
            return back()->with('error', $subjects->message);
        }
        $subjects = $subjects->data;

        return view('feature.Subject.index', compact('subjects'));
        // dd(1);
    }

    /**
     * Display a form create testing data 
     * @return \\illuminate\Http\response
     */
    public function create()
    {
        return view('feature.Subject.add');
    }

    /**
     * store data into database
     * @return \\illuminate\Http\response
     */
    public function store(Request $request)
    {

        try {
            DB::beginTransaction();

            $subject = Subject::create([
                'name' => $request->name,
                'description' => $request->description,
            ]);

            foreach ($request->grids as $grid) {
                SubjectGrid::create([
                    'subject_id' => $subject->id,
                    'grid_name' => $grid['name'],
                    'percentage' => $grid['percentage']
                ]);
            }

            DB::commit();
            return redirect('subject')->with('success', 'Subject created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Problem occurred while trying to create subject.');
        }
        DB::commit();
        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Subject [ ' . ucwords($subject->name) . ' ] was created on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();
        return redirect('subject');
    }

    /**
     * Display form update.
     * @return \\illuminate\Http\response
     */
    public function edit($id)
    {
        $subject = Subject::getSubject($id);
        if (!$subject->data) {
            return back()->with('error', $subject->message);
        }
        $subject = $subject->data;
        return view('feature.Subject.edit', compact('subject'));
    }

    /**
     * Update data to DB\
     *@return \\illuminate\Http\response
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::getSubject($id);
        if (!$subject->data) {
            return back()->with('error', $subject->message);
        }

        try {
            DB::beginTransaction();

            $subject = $subject->data;
            $subject->name = $request->name;
            $subject->description = $request->description;
            $subject->save();

            // Delete old grids
            SubjectGrid::where('subject_id', $subject->id)->delete();

            // Add new grids
            if ($request->has('grids')) {
                foreach ($request->grids as $grid) {
                    SubjectGrid::create([
                        'subject_id' => $subject->id,
                        'grid_name' => $grid['name'],
                        'percentage' => $grid['percentage']
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('subject')->with('success', 'Subject updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Problem occurred while trying to update subject.');
        }
    }

    /**
     * Delete test from DB\
     *@return \\illuminate\Http\response
     */
    public function destroy($id)
    {
        $subject = Subject::getSubject($id);
        if (!$subject->data) {
            return back()->with('error', $subject->message);
        }

        try {
            DB::beginTransaction();

            // Delete related grids first (optional if you use `onDelete('cascade')` in migration)
            $subject->data->grids()->delete();

            // Delete subject
            $subject->data->delete();

            DB::commit();
            return redirect()->route('subject')->with('success', 'Subject and its grids deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Problem occurred while deleting subject.');
        }
    }

    // next crud ---- 
}
