<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Standard;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('standard')->get(); // Fetch all exams with their associated standards
        return view('exam.list', compact('exams'));
    }

    public function create()
    {
        $standards = Standard::all(); // Get all standards for the dropdown
        return view('exam.add', compact('standards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'standard_id' => 'required|exists:standards,id',
            'exam_date' => 'required|date',
            'total_marks' => 'required|integer', // Validate total_marks
            'status' => 'required|string|max:20',
        ]);

        Exam::create($request->all());

        return redirect()->route('exam.index')->with('success', 'Exam added successfully.');
    }

}
