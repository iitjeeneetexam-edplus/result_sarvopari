<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Standard;
use App\Models\Subject;
use App\Models\Subjectsub;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // Display a listing of the subjects
    public function index()
    {
       
        $subjects = Subject::leftjoin('standards', 'standards.id', '=', 'subjects.standard_id')
                            ->select('subjects.*', 'standards.standard_name', 'standards.id as standard_id')
                            ->paginate(5);
                        $subject_subs = [];  

                        foreach ($subjects as $value) {
                            // Store sub-subjects by subject ID
                            $subject_subs[$value->id] = Subjectsub::where('subject_id', $value->id)->get();
                        }

        return view('subjects.list', compact('subjects','subject_subs'));
    }

    public function create()
    {
        $schools = School::select('id', 'school_name')->get();
        $standards = Standard::all(); // Assuming you have a Standard model
        return view('subjects.add', compact('standards','schools'));
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

    //    $validatedData = $request->validate([
    //     'subject_name' => 'required|array',
    //     'subject_name.*' => 'required|string',
    //     'subject_sub_name' => 'required|array',
    //     'subject_sub_name.*' => 'array',
    //     'is_optional' => 'required|array',
    //     'is_optional.*' => 'required|boolean',
    //     'standard_id' => 'required|integer',
    //     'status' => 'required|boolean',
    // ]);

    // Prepare subjects for mass insertion
    foreach ($request['subject_name'] as $index => $subjectName) {
        // Insert the main subject
        if (empty($subjectName)) {
            // Concatenate the sub-subject names if subject name is null or empty
            $subjectName = implode(', ', array_filter($request['subject_sub_name'][$index]));
        }
        $subject = Subject::create([
            'subject_name' => $subjectName,
            'is_optional' => $request['is_optional'][$index], // Add corresponding optional status
            'standard_id' => $request['standard_id'],         // Standard ID
            'status' => $request['status'],                   // Status
        ]);
    
        // Loop through the subject sub-names and insert them
        foreach ($request['subject_sub_name'][$index] as $subIndex => $subjectSubName) {
            // Insert each subject sub-name
            SubjectSub::create([
                'subject_id' => $subject->id,              // Foreign key from the subject
                'subject_name' => (!empty($subjectSubName))?$subjectSubName:null,
            ]);
        }
    }
    // Insert sub-subjects in bulk
            return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }
}
