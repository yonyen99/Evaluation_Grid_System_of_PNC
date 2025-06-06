<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Whoops\Example\bar;

class GenerationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $generations = Generation::getGenerations();
        if (!$generations->data) {
            return back()->with('error', $generations->message);
        }

        $generations = $generations->data;

        return view('feature.generation.index', compact('generations'));
    }

    /**
     * Display a add form
     */
    public function create()
    {
        return view('feature.generation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $generation = new Generation([
                'name' => $request['name'],
                'grade' =>  $request['grade'],
                'description' => $request['description'],
                'teacher_id'  => $request['teacher_id']
            ]);
            $generation->save();
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Have any problem, pls try again!');
        }
        DB::commit();
        return view('feature.generation.index');
    }

    /**
     * Display the specified resource.
     */
    public function edit($id)
    {
        $generation = Generation::getGenerations();
        if (!$generation->data) {
            return back()->with('error', $generation->message);
        }
        $generation = $generation->data;

        return view('feature.generation.edit', compact('generation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $generation = Generation::getGenerations($id);
        if (!$generation->data) {
            return back()->with('error', $generation->message);

        }

        $generation = $generation->data;
        try {
            DB::beginTransaction();
            $generation->name = $request['name'];
            $generation->grade = $request['grade'];
            $generation->description = $request['description'];
            $generation->teacher_id = $request['teacher_id'];
            $generation->update();

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong!');
        }

        DB::commit();

        return redirect()
               ->route('generation')
               ->with('200', 'Generation updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $generation = Generation::getGenerations($id);
        if (!$generation->data) {
            return back()->with('error', $generation->message);
        }

        $generation = $generation->data;

        try {
            DB::beginTransaction();
            $generation->delete();

        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong!');
        }
        DB::commit();

        return redirect()
                ->route('generation')
                ->with('200', 'Delete successfully!');
    }
}
