<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Standard;
use App\Models\Subject;
use Illuminate\Http\Request;

class DivisionController extends Controller
{

    public function index()
    {
        $divisions = Division::with('standard')->paginate(5); 


        $standard = Standard::paginate(5);
        $division = [];  
        foreach($standard as $value){
            $division[$value->id] = Division::where('standard_id', $value->id)->get(); 
        }
        return view('division.list', compact('standard','division'));
    }
    public function create()
    {
        $standards = Standard::all(); 
        return view('division.add', compact('standards')); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'standard_id' => 'required|exists:standards,id', 
        ]);

        Division::create($request->all());

        return redirect()->route('division.index')->with('success', 'Division added successfully.');
    }

    public function getdivisionBystandard($standard_id)
    {
        // Fetch standards based on school_id
        $division = Division::where('standard_id', $standard_id)->get();
        // $Subject = Subject::where('standard_id', $standard_id)->get();
        return response()->json($division);
    }
    public function getSubjectsubBySchool($standard_id)
    {
        $division = Division::where('standard_id', $standard_id)->get();
        $Subject = Subject::where('standard_id', $standard_id)->get();
        return response()->json(['divisions'=>$division,'subjects'=>$Subject]);
    }
    
}
