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
        $query = Subject::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $subjects = $query->orderBy('name')->paginate(10)->appends($request->query());

        return view('feature.subject.index', compact('subjects'));
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'subject_type' => 'required|in:IT Training,General Training',
            'credit'       => 'required|numeric|min:0',
            'nbhours'      => 'required|numeric|min:0',
            'grids'        => 'required|array|min:1',
            'grids.*.name' => 'required|string|max:255',
            'grids.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::beginTransaction();

            $subject = Subject::create([
                'name'         => $request->name,
                'subject_type' => $request->subject_type,
                'credit'       => $request->credit,
                'nbhours'      => $request->nbhours,
                'description'  => $request->description ?? null,
            ]);

            foreach ($request->grids as $grid) {
                SubjectGrid::create([
                    'subject_id'  => $subject->id,
                    'grid_name'   => $grid['name'],
                    'percentage'  => $grid['percentage']
                ]);
            }

            DB::commit();

            $currentUser = auth()->user();
            $logHistory  = new LogHistory([
                'log_header'      => 'create subject',
                'permission_slug' => 'view subject_history',
                'username'        => $currentUser->username,
                'user_id'         => $currentUser->id,
                'description'     => 'Subject [ ' . ucwords($subject->name) . ' ] was created on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
            ]);
            $logHistory->save();

            return redirect('subject')->with('success', 'Subject created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Problem occurred while trying to create subject.');
        }
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

        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Subject [ ' . ucwords($subject->name) . ' ] was updated on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();
        return view('feature.Subject.edit', compact('subject'));
    }

    /**
     * Update data to DB
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'subject_type' => 'required|in:IT Training,General Training',
            'credit'       => 'required|numeric|min:0',
            'nbhours'      => 'required|numeric|min:0',
            'grids'        => 'required|array|min:1',
            'grids.*.name' => 'required|string|max:255',
            'grids.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        $subject = Subject::getSubject($id);
        if (!$subject->data) {
            return back()->with('error', $subject->message);
        }

        try {
            DB::beginTransaction();

            $subject = $subject->data;
            $subject->name         = $request->name;
            $subject->subject_type = $request->subject_type;
            $subject->credit       = $request->credit;
            $subject->nbhours      = $request->nbhours;
            $subject->description  = $request->description ?? null;
            $subject->save();

            // Delete old grids
            SubjectGrid::where('subject_id', $subject->id)->delete();

            // Add new grids
            foreach ($request->grids as $grid) {
                SubjectGrid::create([
                    'subject_id' => $subject->id,
                    'grid_name'  => $grid['name'],
                    'percentage' => $grid['percentage']
                ]);
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

            $currentUser = auth()->user();
            $logHistory  = new LogHistory([
                'log_header'      => 'create role',
                'permission_slug' => 'view role_history',
                'username'        => $currentUser->username,
                'user_id'         => $currentUser->id,
                'description' => 'Subject [ ' . ucwords($subject->data->name) . ' ] was deleted on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
            ]);
            $logHistory->save();

            return redirect()->route('subject')->with('success', 'Subject and its grids deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Problem occurred while deleting subject.');
        }
    }

    // next crud ---- 
}
