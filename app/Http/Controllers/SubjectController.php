<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use App\Models\Subject;
use App\Models\Subjectsub;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // Display a listing of the subjects
    public function index()
    {
        $subjects = Subject::all(); // Retrieve all subjects
        return view('subjects.list', compact('subjects'));
    }

    public function create()
    {
        $standards = Standard::all(); // Assuming you have a Standard model
        return view('subjects.add', compact('standards'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'subject_name.*' => 'required|string',
        //     'subject_sub_name.*.*' => 'nullable|string', // Sub-subjects are optional
        //     'is_optional.*' => 'required|boolean',
        //     'standard_id' => 'required|exists:standards,id',
        //     'status' => 'required|boolean',
        // ]);

       $validatedData = $request->validate([
        'subject_name' => 'required|array',
        'subject_name.*' => 'required|string',
        'subject_sub_name' => 'required|array',
        'subject_sub_name.*' => 'array',
        'is_optional' => 'required|array',
        'is_optional.*' => 'required|boolean',
        'standard_id' => 'required|integer',
        'status' => 'required|boolean',
    ]);

    // Prepare subjects for mass insertion
    foreach ($validatedData['subject_name'] as $index => $subjectName) {
        // Insert the main subject
        $subject = Subject::create([
            'subject_name' => $subjectName,
            'is_optional' => $validatedData['is_optional'][$index], // Add corresponding optional status
            'standard_id' => $validatedData['standard_id'],         // Standard ID
            'status' => $validatedData['status'],                   // Status
        ]);
    
        // Loop through the subject sub-names and insert them
        foreach ($validatedData['subject_sub_name'][$index] as $subIndex => $subjectSubName) {
            // Insert each subject sub-name
            SubjectSub::create([
                'subject_id' => $subject->id,              // Foreign key from the subject
                'subject_name' => $subjectSubName,
            ]);
        }
    }
    // Insert sub-subjects in bulk
            return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }
}
