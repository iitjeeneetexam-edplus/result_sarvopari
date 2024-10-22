<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::all(); // Retrieve all schools
        return view('school.list', compact('schools')); // Pass schools to the view
    }

    public function create()
    {
        return view('add_school'); // Return the view with the form
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'school_name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email|unique:schools',
            'contact_no' => 'required|string|max:15',
            'status' => 'required|in:1,0',
        ]);

        // Create a new school record
        School::create([
            'school_name' => $request->school_name,
            'address' => $request->address,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'status' => $request->status,
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'School added successfully!');
    }
}
