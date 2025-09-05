<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Generation;
use App\Models\LogHistory;
use App\Models\Term;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function Whoops\Example\bar;

class GenerationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allGenerations = Generation::all(); // for dropdown

        // Build query manually instead of getGenerations()
        $query = Generation::query();

        // Apply filter if generation_id is given
        if ($request->filled('generation_id')) {
            $query->where('id', $request->input('generation_id'));
        }

        // Order by latest (optional, you can adjust)
        $query->orderBy('id', 'desc');

        // Paginate (10 per page) & keep query params for filters
        $generations = $query->paginate(10)->appends($request->query());

        return view('feature.generation.index', compact('generations', 'allGenerations'));
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
    /**
     * Store a newly created Generation and its Terms in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name'          => 'required|string|max:255|unique:generations,name',
            'start_year'    => 'required|integer|min:2000|max:2100',
            'end_year'      => 'required|integer|gte:start_year|max:2100',
            'term_name'     => 'required|array|min:1',
            'term_name.*'   => 'required|string|max:50',
            'start_date'    => 'nullable|array',
            'start_date.*'  => 'nullable|date',
            'end_date'      => 'nullable|array',
            'end_date.*'    => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            // Create Generation with year range
            $generation = Generation::create([
                'name'       => $request->input('name'),
                'start_year' => $request->input('start_year'),
                'end_year'   => $request->input('end_year'),
            ]);

            // Create Terms with start_date and end_date
            $termNames  = $request->input('term_name');
            $startDates = $request->input('start_date', []);
            $endDates   = $request->input('end_date', []);

            foreach ($termNames as $key => $termName) {
                Term::create([
                    'name'          => $termName,
                    'generation_id' => $generation->id,
                    'start_date'    => $startDates[$key] ?? null,
                    'end_date'      => $endDates[$key] ?? null,
                ]);
            }

            DB::commit();

            // Log History
            $currentUser = auth()->user();
            LogHistory::create([
                'log_header'      => 'create generation',
                'permission_slug' => 'view generation_history',
                'username'        => $currentUser->username,
                'user_id'         => $currentUser->id,
                'description'     => 'Generation [ ' . ucwords($generation->name) . ' ] was created on [ ' . Carbon::now() . ' ] by ' . $currentUser->username,
            ]);

            return redirect()->route('generation')->with('success', 'Generation and terms created successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', 'Failed to create generation. Please try again!');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $generation = Generation::getGenerationById($id);
        if (!$generation->data) {
            return back()->with('error', $generation->message);
        }
        $generation = $generation->data;

        return view('feature.generation.show', compact('generation'));
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

        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Generation [ ' . ucwords($generation->name) . ' ] was update on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();

        return view('feature.generation.edit', compact('generation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $generationResponse = Generation::getGenerationById($id);
        if (!$generationResponse->data) {
            return back()->with('error', $generationResponse->message);
        }

        $generation = $generationResponse->data;

        // Validate input
        $request->validate([
            'name'          => 'required|string|max:255|unique:generations,name,' . $generation->id,
            'start_year'    => 'required|integer|min:2000|max:2100',
            'end_year'      => 'required|integer|gte:start_year|max:2100',
            'term_name'     => 'required|array|min:1',
            'term_name.*'   => 'required|string|max:50',
            'term_id'       => 'nullable|array',
            'start_date'    => 'nullable|array',
            'start_date.*'  => 'nullable|date',
            'end_date'      => 'nullable|array',
            'end_date.*'    => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            // Update generation fields
            $generation->update([
                'name'       => $request->input('name'),
                'start_year' => $request->input('start_year'),
                'end_year'   => $request->input('end_year'),
            ]);

            // Delete removed terms
            $deletedIds = explode(',', $request->input('deleted_term_ids', ''));
            if (!empty($deletedIds)) {
                Term::whereIn('id', $deletedIds)->delete();
            }

            // Update existing and add new terms
            $termNames  = $request->input('term_name', []);
            $termIds    = $request->input('term_id', []);
            $startDates = $request->input('start_date', []);
            $endDates   = $request->input('end_date', []);

            foreach ($termNames as $index => $termName) {
                $termId    = $termIds[$index] ?? null;
                $startDate = $startDates[$index] ?? null;
                $endDate   = $endDates[$index] ?? null;

                if ($termId) {
                    // Update existing term
                    Term::where('id', $termId)->update([
                        'name'       => $termName,
                        'start_date' => $startDate,
                        'end_date'   => $endDate,
                    ]);
                } else {
                    // Create new term
                    Term::create([
                        'name'          => $termName,
                        'generation_id' => $generation->id,
                        'start_date'    => $startDate,
                        'end_date'      => $endDate,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('generation')->with('success', 'Generation and terms updated successfully!');
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
        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Generation [ ' . ucwords($generation->name) . ' ] was deleted on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();
        return redirect()
            ->route('generation')
            ->with('200', 'Delete successfully!');
    }

    /**
     * export generation record 
     */
    public function generationExport($id)
    {

        $generation = Generation::findOrFail($id);

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=generation.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($generation) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['No', 'id', 'Name', 'Term Name']);
            foreach ($generation->terms as $key => $term) {
                fputcsv($handle, [$key + 1, $generation->id, $generation->name, $term->name]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * import csv
     */
    public function generationImport(Request $request)
    {
        $request->validate([
            'importCsv' => 'required|file|mimes:csv,txt',
        ]);

        DB::beginTransaction();

        $file = $request->file('importCsv');
        $data = array_map('str_getcsv', file($file));
        $header = array_map('trim', $data[0]); // First row = header
        unset($data[0]); // Remove header


        foreach ($data as $row) {
            $rowData = array_combine($header, $row); // Map headers to values

            // Example: insert into generations table
            $generation = Generation::create([
                'name' => $rowData['Generation']
            ]);
            $generation->save();

            // Split terms (delimiter: | )
            $terms = explode('|', $rowData['Terms']);

            foreach ($terms as $term) {
                Term::create([
                    'generation_id' => $generation->id,
                    'name' => trim($term),
                ]);
            }
        }
        DB::commit();
        return back()->with('success', 'CSV imported successfully!');
    }
}
