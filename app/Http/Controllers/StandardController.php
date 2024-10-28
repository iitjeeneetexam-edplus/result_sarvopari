<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Exam;
use App\Models\School;
use App\Models\Standard;
use App\Models\Subject;
use App\Models\Subjectsub;
use Illuminate\Http\Request;

class StandardController extends Controller
{
    public function index(Request $request)
    {
        // $standards = Standard::with('school')->paginate(5); // Retrieve standards with pagination
        // return view('standard.list', compact('standards'));

        $school_session_id = $request->session()->get('school_id');
        $standards = Standard::with('school')
        ->whereHas('school', function($query) use ($school_session_id) {
            $query->where('id', $school_session_id);
        })
            ->paginate(5);
         return view('standard.list', compact('standards'));
    }

    public function create(Request $request)
    {
        $schools=School::where('id',$request->session()->get('school_id'))->first();
        // $schools_Selected = School::where('id',$request->session()->get('school_id'))->first(); 
        return view('standard.add', compact('schools')); 
    }

    public function store(Request $request)
    {
        $count=Standard::where('school_id',$request->school_id)->where('standard_name',$request->standard_name)->count();
        if($count==0){
            $request->validate([
                'school_id' => 'required|exists:schools,id', // Validate school_id exists in the schools table
                'standard_name' => 'required|string|max:255',
                // 'status' => 'required|in:1,0', // Validate status is either 'active' or 'inactive'
            ]);
    
            Standard::create([
                'school_id' => $request->school_id,
                'standard_name' => $request->standard_name,
                'status' => $request->status,
            ]);
            return redirect()->route('standards.index')->with('success', 'Standard added successfully.');
        } 
       
        return redirect()->route('standards.index')->with('error', 'Standard Already Exists successfully.');

      
        
    }
    public function edit($id,Request $request){
        $schools = School::where('id',$request->session()->get('school_id'))->first(); 
        $data=Standard::where('id',$id)->first();
        return view('standard.edit', compact('data','schools'));
    }
    public function update(Request $request){
        $standard = Standard::findOrFail($request->id);

        $request->validate([
            'school_id' => 'required|exists:schools,id', 
            'standard_name' => 'required|string|max:255|unique:standards,standard_name,' . $standard->id . ',id,school_id,' . $request->school_id,
            'status' => 'required|in:1,0', 
        ]);

        $standard->update([
            'school_id' => $request->school_id,
            'standard_name' => $request->standard_name,
            'status' => $request->status,
        ]);

        return redirect()->route('standards.index')->with('success', 'Standard updated successfully.');

    }
    public function delete($id){
        $standard = Standard::findOrFail($id);

        // Delete the school record
        $standard->delete();
    
        // Optionally, return a response or redirect
        return redirect()->route('standards.index')->with('success', 'Standard deleted successfully.');
    
    }
    public function getStandardsBySchool($school_id)
    {
        $standards = Standard::where('school_id', $school_id)->get();

        return response()->json($standards);
    }
    public function getSubjectsubBySchool($subject_id){

        $subjects = Subject::where('id',$subject_id)->first();

        $subject_sub=Subjectsub::where('subject_id',$subject_id)->get();
        return response()->json(['optional'=>$subjects,'subject_sub'=>$subject_sub]);
    }
    public function getdivisionBydivisionsubject($standard_id){
        $division = Division::where('standard_id', $standard_id)->get();
        $Subject = Subject::where('standard_id', $standard_id)->get();
        $exams = Exam::where('standard_id', $standard_id)->get();
        return response()->json(['divisions'=>$division,'subjects'=>$Subject,'exams'=>$exams]);

    }
}
