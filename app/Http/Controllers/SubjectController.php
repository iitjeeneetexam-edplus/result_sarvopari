<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // Display a listing of the subjects
    public function index()
    {
        $subjects = Subject::all(); // Retrieve all subjects
        return view('subjects.list', compact('subjects'));
    }

    // Show the form for creating a new subject
    public function create()
    {
        $standards = Standard::all(); // Assuming you have a Standard model
        return view('subjects.add', compact('standards'));
    }

    // Store a newly created subject in storage
    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'is_optional' => 'boolean',
            'standard_id' => 'required|exists:standards,id', // Validate standard_id
            'status' => 'required|boolean',
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }
}
