<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Generation;
use App\Models\Term;
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
        return view('feature.generation.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $generation = new Generation([
                'name'         => $request['name'],
            ]);
            $generation->save();
            try {
                $termNames = $request['term_name'];
                foreach ($termNames as $key => $value) {
                    $Terms = new Term([
                        'name'           => $termNames[$key],
                        'generation_id'  => $generation->id,
                    ]);
                    $Terms->save();
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                return back()->with('error', 'Terms cant add, pls try again!');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Generation cant add , pls try again!');
        }
        DB::commit();
        return redirect()->route('generation');
    }

    /**
     * Display the specified resource.
     */
    public function edit($id)
    {
        $generation = Generation::getGenerationById($id);
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
        $generation = Generation::getGenerationById($id);
        if (!$generation->data) {
            return back()->with('error', $generation->message);
        }

        $generation = $generation->data;

        try {
            DB::beginTransaction();

            // Update generation name
            $generation->name = $request->input('name');
            $generation->update();

            // Delete removed terms
            $deletedIds = explode(',', $request->input('deleted_term_ids', ''));
            if (!empty($deletedIds)) {
                Term::whereIn('id', $deletedIds)->delete();
            }

            // Update existing and newly added terms
            $termNames = $request->input('term_name', []);
            $termIds = $request->input('term_id', []);

            foreach ($termNames as $index => $termName) {
                $termId = $termIds[$index] ?? null;

                if ($termId) {
                    // Update existing term
                    Term::where('id', $termId)->update([
                        'name' => $termName,
                    ]);
                } else {
                    // Create new term
                    Term::create([
                        'name' => $termName,
                        'generation_id' => $generation->id,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('generation')->with('200', 'Generation and terms updated successfully!');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong during update!');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $generation = Generation::getGenerationById($id);
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
