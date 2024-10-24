<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Standard;
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
            'standard_name' => 'required|string|max:255|unique:standards',
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
        // Fetch standards based on school_id
        $standards = Standard::where('school_id', $school_id)->get();

        // Return the standards as JSON
        return response()->json($standards);
    }

}
