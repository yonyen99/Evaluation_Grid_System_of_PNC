<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Classe;
use App\Models\Generation;
use App\Models\LogHistory;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index()
    {
        $generations = Generation::with(['terms.classes', 'classes'])->get();
        return view('feature.term.index', compact('generations'));
    }

    public function storeClass(Request $request, $termId)
    {
        $request->validate([
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $term = Term::findOrFail($termId);

        // Sync or attach class_ids to pivot table
        $term->classes()->syncWithoutDetaching($request->class_ids); // avoids duplicate
        $currentUser = auth()->user();
        $logHistory  = new LogHistory([
            'log_header'      => 'create role',
            'permission_slug' => 'view role_history',
            'username'        => $currentUser->username,
            'user_id'         => $currentUser->id,
            'description'     => 'Term [ ' . ucwords($request->term) . ' ] was created on [ ' . Carbon::now() . ' ] by ' . $currentUser->username . ' user',
        ]);
        $logHistory->save();
        return redirect()->route('term.index')->with('success', 'Classes added to term successfully.');
    }
}
