<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Standard;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function create()
    {
        $standards = Standard::all(); // Fetch all standards
        return view('divisions.create', compact('standards')); // Pass standards to the view
    }

    public function store(Request $request)
    {
        $request->validate([
            'division_name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'standard_id' => 'required|exists:standards,id', // Validate standard_id
        ]);

        Division::create($request->all());

        return redirect()->route('divisions.index')->with('success', 'Division added successfully.');
    }

    public function index()
    {
        $divisions = Division::with('standard')->get(); // Eager load the standard relationship
        return view('divisions.index', compact('divisions'));
    }
}
