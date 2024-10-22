<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Standard;
use Illuminate\Http\Request;

class StandardController extends Controller
{
    public function index()
    {
        $standards = Standard::with('school')->paginate(10); // Retrieve standards with pagination
        return view('standard.list', compact('standards'));
    }

    public function create()
    {
        $schools = School::all(); // Retrieve all schools to populate the dropdown
        return view('standards.add', compact('schools')); // Pass schools to the view
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id', // Validate school_id exists in the schools table
            'standard_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive', // Validate status is either 'active' or 'inactive'
        ]);

        Standard::create([
            'school_id' => $request->school_id,
            'standard_name' => $request->standard_name,
            'status' => $request->status,
        ]);

        return redirect()->route('standards.index')->with('success', 'Standard added successfully.');
    }

}
