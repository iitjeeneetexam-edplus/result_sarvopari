<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\School;
use App\Models\Standard;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('standard')->paginate(5); 
        // echo "<pre>";print_r($exams);exit;// Fetch all exams with their associated standards
        return view('exam.list', compact('exams'));
    }

    public function create()
    {
        $schools = School::select('id', 'school_name')->get();
        $standards = Standard::all(); // Get all standards for the dropdown
        return view('exam.add', compact('standards','schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_name' => 'required|string|max:255|unique:exams',
            'standard_id' => 'required|exists:standards,id',
            'date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
        ]);

        Exam::create($request->all());

        return redirect()->route('exam.index')->with('success', 'Exam added successfully.');
    }

}
