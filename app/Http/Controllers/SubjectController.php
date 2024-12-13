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
use App\Models\Subjectsub;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $school_session_id = $request->session()->get('school_id');
        $subjects = Subject::leftjoin('standards', 'standards.id', '=', 'subjects.standard_id')
                            ->select('subjects.*', 'standards.standard_name', 'standards.id as standard_id')
                            ->where('standards.school_id', $school_session_id)
                            ->paginate(50);
                        $subject_subs = [];  

                        foreach ($subjects as $value) {
                            $subject_subs[$value->id] = Subjectsub::where('subject_id', $value->id)->get();
                        }

        return view('subjects.list', compact('subjects','subject_subs'));
    }

    public function create(Request $request)
    {
        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->first();
        $standards = Standard::all(); // Assuming you have a Standard model
        return view('subjects.add', compact('standards','schools'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'subject_name.*' => 'required|string',
        //     'subject_sub_name.*.*' => 'nullable|string', // Sub-subjects are optional
        //     'is_optional.*' => 'required|boolean',
        //     'standard_id' => 'required|exists:standards,id',
        //     'status' => 'required|boolean',
        // ]);

    //    $validatedData = $request->validate([
    //     'subject_name' => 'required|array',
    //     'subject_name.*' => 'required|string',
    //     'subject_sub_name' => 'required|array',
    //     'subject_sub_name.*' => 'array',
    //     'is_optional' => 'required|array',
    //     'is_optional.*' => 'required|boolean',
    //     'standard_id' => 'required|integer',
    //     'status' => 'required|boolean',
    // ]);

    // Prepare subjects for mass insertion
    foreach ($request['subject_name'] as $index => $subjectName) {
        // Insert the main subject
        if (empty($subjectName)) {
            // Concatenate the sub-subject names if subject name is null or empty
            $subjectName = implode(', ', array_filter($request['subject_sub_name'][$index]));
        }
        
        $subject = Subject::create([
            'subject_name' => $subjectName,
            'is_optional' => $request['is_optional'][$index], // Add corresponding optional status
            'standard_id' => $request['standard_id'],         // Standard ID
            'status' => $request['status'],                   // Status
        ]);
    
        // Loop through the subject sub-names and insert them
        if(!empty(array_filter($request['subject_sub_name'][$index]))){
            foreach ($request['subject_sub_name'][$index] as $subIndex => $subjectSubName) {
                // Insert each subject sub-name
                if (!empty($subjectSubName)) {
                    SubjectSub::create([
                        'subject_id' => $subject->id,              // Foreign key from the subject
                        'subject_name' => $subjectSubName,
                    ]);
                }
            }
        }
        
    }
    // Insert sub-subjects in bulk
            return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }
    public function edit($id,Request $request){
        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->first();
        $standards = Standard::all();

        $data=Subject::where('id',$id)->first();
        if(!empty($data->standard_id)){
            $school_id = Standard::where('id',$data->standard_id)->first();

        }else
        {
            $school_id='';
        }
        $subject_sub = Subjectsub::where('subject_id',$id)->get()->toarray();
        // echo "<pre>";print_r($subject_sub);exit;
        return view('subjects.edit', compact('school_id','schools','data','subject_sub'));
    }
    public function update(Request $request){
        
        foreach ($request['subject_name'] as $index => $subjectName) {
            // Insert the main subject
            if (empty($subjectName)) {
                if (!empty(array_filter($request['subject_sub_name'][$index]))) {
                $subjectName = implode(', ', array_filter($request['subject_sub_name'][$index]));
                }
                else{
                    
                }
            }
            
            // if (is_array($subjectName)) {
            //     $subjectName = implode(', ', $request['subject_sub_name'][$index]); // Convert array to comma-separated string
            // }
            
            $subject = Subject::find($request['subject_id']); 
            if(!empty($subject)){
                $delet = Subject::where('id', $request['subject_id'])->delete();
            }
            
            if ($subject || $delet) {
                $newSubject = Subject::create([
                    'subject_name' => $subjectName,
                    'is_optional' => $request['is_optional'][$index], 
                    'standard_id' => $request['standard_id'],         
                    'status' => $request['status'],                   
                ]);
            
            }
            if (empty($subjectName)) {
                foreach ($request['subject_sub_name'][$index] as $subIndex => $subjectSubName) {
                    $subjectSub = Subjectsub::where('subject_id',$request['subject_id'])->first();
                    $subjectSub->delete();
                    if ($subjectSub) {
                        $newSubject = Subjectsub::create([
                            'subject_id' => $request['subject_id'],              
                            'subject_name' => $subjectSubName,                 
                        ]);                        
                    }
                }
            }
        }
        
        
        return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }
    public function delete($id){
      
        if (is_string($id)) {
            $id = explode(',', $id); 
        }
        $standard_id = Subject::whereIn('id', $id)->pluck('standard_id')->toArray();
        if (!empty($standard_id)) {
            Subject::whereIn('id', $id)->delete();
            Subjectsub::whereIn('subject_id', $id)->delete();
        }
        $division_ids = Division::whereIn('standard_id', $standard_id)->pluck('id')->toArray();
        if (!empty($division_ids)) {
            Division::whereIn('id', $division_ids)->delete();
            Exam::whereIn('standard_id', $standard_id)->delete();
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
        return redirect()->back()->with('success', 'Subjects deleted successfully!');
    }
}
