<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class studentDetailController extends Controller
{
   public function index()
    {
        $details = StudentDetail::with('student')->paginate(10);
        return view('student_details.index', compact('details'));
    }

    public function create()
    {
        $students = Student::all(); // For dropdown
        return view('student_details.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id'   => 'required|exists:students,id',
            'father'       => 'nullable|string',
            'mother'       => 'nullable|string',
            'phone'        => 'nullable|string',
            'address'      => 'nullable|string',
            'mom_contract' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('mom_contract')) {
            $data['mom_contract'] = $request->file('mom_contract')->store('contracts', 'public');
        }

        StudentDetail::create($data);

        return redirect()->route('student-details.index')->with('success', 'Student detail created successfully.');
    }

    public function show(StudentDetail $studentDetail)
    {
        return view('student_details.show', compact('studentDetail'));
    }

    public function edit(StudentDetail $studentDetail)
    {
        $students = Student::all();
        return view('student_details.edit', compact('studentDetail', 'students'));
    }

    public function update(Request $request, StudentDetail $studentDetail)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'father'     => 'nullable|string',
            'mother'     => 'nullable|string',
            'phone'      => 'nullable|string',
            'address'    => 'nullable|string',
            'mom_contract' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('mom_contract')) {
            $data['mom_contract'] = $request->file('mom_contract')->store('contracts', 'public');
        }

        $studentDetail->update($data);

        return redirect()->route('student-details.index')->with('success', 'Student detail updated successfully.');
    }

    public function destroy(StudentDetail $studentDetail)
    {
        $studentDetail->delete();
        return redirect()->route('student-details.index')->with('success', 'Student detail deleted successfully.');
    }
}
