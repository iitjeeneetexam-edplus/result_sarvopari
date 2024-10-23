<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Standard;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('subject')->get(); // Fetch all exams with their associated standards
        return view('exam.list', compact('exams'));
    }

    public function create()
    {
        $subjects = Subject::all(); // Get all standards for the dropdown
        return view('exam.add', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'total_marks' => 'required|integer', 
        ]);

        Exam::create($request->all());

        return redirect()->route('exam.index')->with('success', 'Exam added successfully.');
    }

}
