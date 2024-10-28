<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Standard;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;

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

        
        return redirect()->route('schools')->with('success', 'School added successfully!');
    }
    public function edit(Request $request,$id){
        
         $data=School::where('id',$id)->first();
         return view('school.edit', compact('data'));
         
    }
    public function update(Request $request){
        $school = School::findOrFail($request->id);

        // Validate the request data, allowing the current school's email and name to be unique to itself
        $request->validate([
            'school_name' => 'required|string|max:255|unique:schools,school_name,' . $request->id,
            'address' => 'required|string',
            'email' => 'required|email|unique:schools,email,' . $request->id,
            'contact_no' => 'required|regex:/[0-9]{10}/',
            'status' => 'required|in:1,0',
        ]);
    
        // Update the school record
        $school->update([
            'school_name' => $request->school_name,
            'address' => $request->address,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'status' => $request->status,
        ]);
    
        // Optionally, return a response or redirect
        return redirect()->route('schools')->with('success', 'School updated successfully.');
    
    }
    
    public function delete(Request $request,$id){
        $school = School::findOrFail($id);

        // Delete the school record
        $school->delete();
    
        // Optionally, return a response or redirect
        return redirect()->route('schools')->with('success', 'School deleted successfully.');
    
    }
    public function view($id,Request $request){
        $standards = Standard::with('school')
            ->whereHas('school', function($query) use ($id) {
                $query->where('id', $id);
            })
        ->paginate(5);
       //set session
        $request->session()->put('school_id', $id);
       
       
        $value = $request->session()->get('school_id');
       
        return view('standard.list', compact('standards')); 
    }
}
