<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Exam;
use App\Models\Marks;
use App\Models\Standard;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use Illuminate\Http\Request;

class DivisionController extends Controller
{

    public function index(Request $request)
    {
        // $divisions = Division::with('standard')->paginate(5); 
        $school_session_id = $request->session()->get('school_id');
        $division = Division::leftJoin('standards', 'standards.id', '=', 'division.standard_id')
        ->select('division.*', 'standards.standard_name')
        ->where('standards.school_id', $school_session_id)
        ->paginate(5);
        $standard = Standard::paginate(5);
        // $division = [];  
        // foreach($standard as $value){
        //     $division[$value->id] = Division::where('standard_id', $value->id)->get(); 
        // }
        return view('division.list', compact('standard','division'));
    }
    public function create(Request $request)
    {
        $standards = Standard::where('school_id',$request->session()->get('school_id'))->get(); 
        return view('division.add', compact('standards')); 
    }

    public function store(Request $request)
    {
        $count=Division::where('standard_id',$request->standard_id)->where('division_name',$request->division_name)->count();
        if($count==0){
        $request->validate([
            'division_name' => 'required|string|max:255',
            'status' => 'required|boolean',
            'standard_id' => 'required|exists:standards,id', 
        ]);
        Division::create($request->all());
        return redirect()->route('division.index')->with('success', 'Division added successfully.');
   
        }else{
            return redirect()->route('division.index')->with('error', 'Division Already Exists successfully.');

            }
       
    }
    public function edit($id,Request $request){
        $standards = Standard::where('id',$request->session()->get('school_id'))->get(); 
    
        $data = Division::leftJoin('standards', 'standards.id', '=', 'division.standard_id')
        ->select('division.*', 'standards.standard_name')
        ->where('division.id',$id)->first();
        return view('division.edit', compact('data','standards'));
    }
    public function update(Request $request){
        $division = Division::findOrFail($request->id);

    // Validate the request data
        $request->validate([
            'division_name' => 'required|string|max:255|unique:division,division_name,' . $request->id . ',id,standard_id,' . $request->standard_id,
            'status' => 'required|boolean',
            'standard_id' => 'required|exists:standards,id',

        ]);

        // Update the division record
        $division->update([
            'division_name' => $request->division_name,
            'status' => $request->status,
            'standard_id' => $request->standard_id,
        ]);

        // Redirect back with a success message
        return redirect()->route('division.index')->with('success', 'Division updated successfully.');


    }
    public function delete($id){
        if (is_string($id)) {
            $id = explode(',', $id); 
        }
        $standard_id = Division::whereIn('id', $id)->pluck('standard_id')->toArray();
        if (!empty($standard_id)) {
            Division::whereIn('id', $id)->delete();
            Exam::whereIn('standard_id', $standard_id)->delete();
            $student_ids = Student::whereIn('division_id', $id)->pluck('id')->toArray();
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
        return redirect()->route('division.index')->with('success', 'Division deleted successfully.');
    
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
    public function getstandardByexam($standard_id){
        $exam = Exam::where('standard_id', $standard_id)->get();
        // $Subject = Subject::where('standard_id', $standard_id)->get();
        return response()->json($exam);
    }
    
}
