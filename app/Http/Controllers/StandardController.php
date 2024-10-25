<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Standard;
use App\Models\Subject;
use App\Models\Subjectsub;
use Illuminate\Http\Request;

class StandardController extends Controller
{
    public function index()
    {
        $standards = Standard::with('school')->paginate(5); // Retrieve standards with pagination
        return view('standard.list', compact('standards'));
    }

    public function create()
    {
        $schools = School::all(); // Retrieve all schools to populate the dropdown
        return view('standard.add', compact('schools')); // Pass schools to the view
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id', // Validate school_id exists in the schools table
            'standard_name' => 'required|string|max:255',
            'status' => 'required|in:1,0', // Validate status is either 'active' or 'inactive'
        ]);

        Standard::create([
            'school_id' => $request->school_id,
            'standard_name' => $request->standard_name,
            'status' => $request->status,
        ]);

        return redirect()->route('standards.index')->with('success', 'Standard added successfully.');
    }

    public function getStandardsBySchool($school_id)
    {
        $standards = Standard::where('school_id', $school_id)->get();

        return response()->json($standards);
    }
    public function getSubjectsubBySchool($subject_id){

        $subjects = Subject::where('id',$subject_id)->first();

        $subject_sub=Subjectsub::where('subject_id',$subject_id)->get();
        return response()->json(['optional'=>$subjects,'subject_sub'=>$subject_sub]);
    }
}
