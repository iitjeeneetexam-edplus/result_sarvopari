<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Standard;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::with('standard')->get(); // Eager load the standard relationship
        return view('division.list', compact('divisions'));
    }

    public function create()
    {
        $standards = Standard::all(); // Fetch all standards
        return view('division.add', compact('standards')); // Pass standards to the view
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'standard_id' => 'required|exists:standards,id', // Validate standard_id
        ]);

        Division::create($request->all());

        return redirect()->route('division.index')->with('success', 'Division added successfully.');
    }

    
}
