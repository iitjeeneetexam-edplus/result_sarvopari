<?php

namespace App\Http\Controllers;

use App\Models\Standard;
use Illuminate\Http\Request;

class StandardController extends Controller
{
    public function index()
    {
        $standards = Standard::with('school')->paginate(10); // Retrieve standards with pagination
        return view('standard.list', compact('standards'));
    }

    
}
