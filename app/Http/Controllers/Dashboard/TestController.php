<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tests = Test::getTests();
        if( !$tests->data ){
            return back()->with('error', $tests->message);
        }
        $tests = $tests->data;

        return view('feature.Test.index',compact('tests'));
    }

    /**
     * Display a form create testing data 
     * @return \\illuminate\Http\response
     */
    public function create(){
        return view('feature.Test.add');
    }

    /**
     * store data into database
     * @return \\illuminate\Http\response
     */
    public function store(Request $request){

        try {
            DB::beginTransaction();
            $test = new Test([
                'name'   => $request['name'],
                'salery' => $request['salery']
            ]);
            $test->save();
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Problem occured while trying to create Test record into database!');
        }
        DB::commit();
        return view('feature.Test.index');
    }

    /**
     * Display form update.
     * @return \\illuminate\Http\response
     */
    public function edit($id){
        $test = Test::getTest($id);
        if( !$test->data ){
            return back()->with('error', $test->message);
        }
        $test = $test->data;
        return view('feature.Test.edit',compact('test'));
    }

    /**
     * Update data to DB\
     *@return \\illuminate\Http\response
     */
    public function update(Request $request, $id){
        $test = Test::getTest($id);
        if( !$test->data ){
            return back()->with('error', $test->message);
        }
        $test = $test->data;
        try {
           DB::beginTransaction();

            $test->name     = $request['name'];
            $test->salery   = $request['salery'];
            $test->update();

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Problem occured while trying to update test record into database!');
        }
        DB::commit();

        return redirect()
            ->route('test')
            ->with('success', 'test record updated successfully');
    }

    /**
     * Delete test from DB\
     *@return \\illuminate\Http\response
     */
    public function destroy($id){
        $test = Test::getTest($id);
        if( !$test->data ){
            return back()->with('error', $test->message);
        }
        $test = $test->data;
        try {
            DB::beginTransaction();
            $test->delete();
        } catch (\Throwable $th) {
           DB::rollBack();
            return back()->with('error', 'Problem occured while trying to delete test record from database!');
        }
        DB::commit();
    
        return redirect()
            ->route('test')
            ->with('success', 'test record deleted successfully');
    }
    // next crud ---- 
}
