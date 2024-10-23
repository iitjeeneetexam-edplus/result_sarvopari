<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Standard;
use Illuminate\Http\Request;

class DivisionController extends Controller
{

    public function index()
    {
        $divisions = Division::with('standard')->get(); 
        return view('division.list', compact('divisions'));
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

    
}
