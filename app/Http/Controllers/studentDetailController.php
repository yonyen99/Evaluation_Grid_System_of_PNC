<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\studentDetail;
use App\Models\Student;
class studentDetailController extends Controller
{
   public function index()
    {
        $families = studentDetail::with('student')->latest()->paginate(10);
        return view('feature.students.studentDetail.index', compact('families'));
    }

    /**
     * Show the form for creating a new family info.
     */
    public function create($studentId)
    {
        $student = Student::findOrFail($studentId);
        return view('feature.students.studentDetail.add', compact('student'));
    }

    /**
     * Store a newly created family info in storage.
     */
    public function store(Request $request, $student)
    {
        $validated = $request->validate([
            'father' => 'required|string|max:255',
            'mother' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'mom_contract' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $validated['student_id'] = $student;

        if ($request->hasFile('mom_contract')) {
            $validated['mom_contract'] = $request->file('mom_contract')->store('contracts', 'public');
        }

        studentDetail::create($validated);

        return redirect()->route('studentShow', $student)
            ->with('success', 'Family information added successfully.');
    }



    /**
     * Display the specified family info.
     */
    public function show($id)
    {
        $family = studentDetail::with('student')->findOrFail($id);
        return view('feature.students.studentDetail.add', compact('family'));
        
    }

    /**
     * Show the form for editing the specified family info.
     */
    public function edit($student)
    {
        // Get the student
        $student = Student::findOrFail($student);

        // Get the studentDetail for this student
        $family = $student->studentDetail;

        if (!$family) {
            return redirect()->route('studentShow', $student)
                ->with('error', 'No family information found. Please create it first.');
        }

        return view('feature.students.studentDetail.edit', compact('student', 'family'));
    }


    /**
     * Update the specified family info in storage.
     */
    public function update(Request $request, $student)
    {
        $family = studentDetail::where('student_id', $student)->firstOrFail();

        $validated = $request->validate([
            'father' => 'required|string|max:255',
            'mother' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'mom_contract' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('mom_contract')) {
            $validated['mom_contract'] = $request->file('mom_contract')->store('contracts', 'public');
        }

        $family->update($validated);

        return redirect()->route('studentShow', $student)
            ->with('success', 'Family information updated successfully.');
    }



    /**
     * Remove the specified family info from storage.
     */
    public function destroy($id)
    {
        $family = studentDetail::findOrFail($id);
        $studentId = $family->student_id;
        $family->delete();

        return redirect()
            ->route('studentShow', $studentId)
            ->with('success', 'Family information deleted successfully.');
    }
}
