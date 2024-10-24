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
        $request->validate([
            'subject_name.*' => 'required|string',
            'subject_sub_name.*.*' => 'nullable|string', // Sub-subjects are optional
            'is_optional.*' => 'required|boolean',
            'standard_id' => 'required|exists:standards,id',
            'status' => 'required|boolean',
        ]);

        // Loop through main subjects and save
        foreach ($request->subject_name as $index => $subject_name) {
            $subject = new Subject();
            $subject->subject_name = $subject_name;
            $subject->standard_id = $request->standard_id; // Assume each subject is linked to a standard
            $subject->status = $request->status;
            $subject->save();

            // Check if there are sub-subjects for this main subject
            if (isset($request->subject_sub_name[$index])) {
                foreach ($request->subject_sub_name[$index] as $sub_name) {
                    if (!empty($sub_name)) { // Ensure the sub name is not empty
                        $subSubject = new Subjectsub();
                        $subSubject->subject_id = $subject->id; // Assuming there's a foreign key for linking
                        $subSubject->subject_name = $sub_name;
                        $subSubject->save();
                    }
                }
            }
        }
        
        return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }
}
