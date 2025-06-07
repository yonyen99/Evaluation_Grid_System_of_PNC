<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::getSubjects();
        // dd($subjects);
        if( !$subjects->data ){
            return back()->with('error', $subjects->message);
        }
        $subjects = $subjects->data;

        return view('feature.Subject.index',compact('subjects'));
        // dd(1);
    }

    /**
     * Display a form create testing data 
     * @return \\illuminate\Http\response
     */
    public function create(){
        return view('feature.Subject.add');
    }

    /**
     * store data into database
     * @return \\illuminate\Http\response
     */
    public function store(Request $request){

        try {
            DB::beginTransaction();
            $subject = new Subject([
                'name'   => $request['name'],
                'description' => $request['description']
            ]);
            $subject->save();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Problem occured while trying to create Subject record into database!');
        }
        DB::commit();
        return redirect('subject');
    }

    /**
     * Display form update.
     * @return \\illuminate\Http\response
     */
    public function edit($id){
        $subject = Subject::getSubject($id);
        if( !$subject->data ){
            return back()->with('error', $subject->message);
        }
        $subject = $subject->data;
        return view('feature.Subject.edit',compact('subject'));
    }

    /**
     * Update data to DB\
     *@return \\illuminate\Http\response
     */
    public function update(Request $request, $id){
        $subject = Subject::getSubject($id);
        if( !$subject->data ){
            return back()->with('error', $subject->message);
        }
        $subject = $subject->data;
        try {
           DB::beginTransaction();

            $subject->name     = $request['name'];
            $subject->description   = $request['description'];
            $subject->update();

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Problem occured while trying to update test record into database!');
        }
        DB::commit();

        return redirect()
            ->route('subject')
            ->with('success', 'subject record updated successfully');
    }

    /**
     * Delete test from DB\
     *@return \\illuminate\Http\response
     */
    public function destroy($id){
        $subject = Subject::getSubject($id);
        if( !$subject->data ){
            return back()->with('error', $subject->message);
        }
        $subject = $subject->data;
        try {
            DB::beginTransaction();
            $subject->delete();
        } catch (\Throwable $th) {
           DB::rollBack();
            return back()->with('error', 'Problem occured while trying to delete test record from database!');
        }
        DB::commit();
    
        return redirect()
            ->route('subject')
            ->with('success', 'subject record deleted successfully');
    }
    // next crud ---- 
}
