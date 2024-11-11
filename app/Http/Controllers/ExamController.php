<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Exam;
use App\Models\Marks;
use App\Models\School;
use App\Models\Standard;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
        $school_session_id = $request->session()->get('school_id');
        $exams = Exam::leftJoin('standards', 'standards.id', '=', 'exams.standard_id')
        ->select('exams.*', 'standards.standard_name')
        ->where('standards.school_id', $school_session_id)
        ->paginate(5);
        return view('exam.list', compact('exams'));
    }

    public function create(Request $request)
    {
        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->get();
        $standards = Standard::all(); 
        return view('exam.add', compact('standards','schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'exam_name' => 'required|string|max:255',
            'standard_id' => 'required|exists:standards,id',
            'date' => 'required',
            'result_date' => 'required',
            'exam_year' => 'required',
        ]);

        Exam::create($request->all());

        return redirect()->route('exam.index')->with('success', 'Exam added successfully.');
    }
    public function edit($id,Request $request){
        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->get();
        $data=Exam::where('id',$id)->first();
        $standard = Standard::where('id',$data->standard_id)->first();
        if(!empty($standard->school_id)){
            $selected_School = School::where('id',$standard->school_id)->first(); 
        }else{
            $selected_School = new School();
            $selected_School->id = '';
        }

        return view('exam.edit', compact('data','schools','selected_School'));
    }
    public function update(Request $request){
        $exam = Exam::findOrFail($request->id);

        $request->validate([
            'exam_name' => 'required|string|max:255|unique:exams,exam_name,' . $exam->id . ',id,standard_id,' . $request->standard_id,
            'standard_id' => 'required|exists:standards,id',
            'date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
        ]);

        $exam->update($request->all());
        return redirect()->route('exam.index')->with('success', 'Exam updated successfully.');

    }
    public function delete($id){
        if (is_string($id)) {
            $id = explode(',', $id); 
        }
        $standard_id = Exam::whereIn('id', $id)->pluck('standard_id')->toArray();
 
        if (!empty($standard_id)) {
            $division_ids=Division::whereIn('standard_id', $standard_id)->pluck('id')->toArray();

            Exam::whereIn('id', $id)->delete();

            $student_ids = Student::whereIn('division_id', $division_ids)->pluck('id')->toArray();

            if (!empty($student_ids)) {
                Student::whereIn('id', $student_ids)->delete();

                $studentsubject_ids = StudentSubject::whereIn('student_id', $student_ids)->pluck('id')->toArray();

                if (!empty($studentsubject_ids)) {
                    StudentSubject::whereIn('id', $studentsubject_ids)->delete();
                    
                }

                $marks_ids = Marks::whereIn('student_id', $student_ids)->pluck('id')->toArray();

                if (!empty($marks_ids)) {
                    Marks::whereIn('id', $marks_ids)->delete();
                }
            }
        
    }
    
        return redirect()->route('exam.index')->with('success', 'Exam deleted successfully.');
    }

}
