<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::paginate(5); 
        return view('school.list', compact('schools'));
    }

    public function create()
    {
        return view('school.add'); 
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'school_name' => 'required|string|max:255|unique:schools',
            'address' => 'required|string',
            'email' => 'required|email|unique:schools',
            'contact_no' => 'required|regex:/[0-9]{10}/',
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

        
        return redirect()->route('schools.index')->with('success', 'School added successfully!');
    }
}
