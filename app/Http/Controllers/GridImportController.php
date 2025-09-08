<?php

namespace App\Http\Controllers;

use App\Models\GridType;
use App\Models\Student;
use App\Models\SubjectGrid;
use Illuminate\Http\Request;

class GridImportController extends Controller
{
    //
     public function import(Request $request, SubjectGrid $grid)
    {
        $file = $request->file('csv_file');
        $handle = fopen($file, 'r');
        $header = fgetcsv($handle); // read header row

        // normalize headers
        $header = array_map('strtolower', $header);

        $firstNameIndex = array_search('first name', $header);
        $lastNameIndex = array_search('last name', $header);
        $totalIndex = array_search('total', $header);

        if ($firstNameIndex === false || $lastNameIndex === false || $totalIndex === false) {
            return back()->with('error', 'CSV must contain First Name, Last Name, and Total columns.');
        }

        while (($row = fgetcsv($handle)) !== false) {
            $firstName = trim($row[$firstNameIndex]);
            $lastName  = trim($row[$lastNameIndex]);
            $total     = trim($row[$totalIndex]);

            // find student by first+last name
            $student = Student::where('first_name', $firstName)
                              ->where('last_name', $lastName)
                              ->first();

            if ($student) {
                // find grid_type row
                $gridType = GridType::where('student_id', $student->id)
                    ->where('subject_grid_id', $grid->id)
                    ->first();

                if ($gridType) {
                    $gridType->update(['value' => $total]);
                } else {
                    GridType::create([
                        'student_id'       => $student->id,
                        'subject_grid_id'  => $grid->id,
                        'value'            => $total,
                        'class_id'         => $grid->subject->class_id ?? null, // adjust depending on schema
                        'subject_id'       => $grid->subject_id,
                    ]);
                }
            }
        }

        fclose($handle);
        return back()->with('success', 'CSV imported successfully!');
    }
}
