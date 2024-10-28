<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Standard;
use App\Models\Subject;
use App\Models\Subjectsub;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // Display a listing of the subjects
    public function index(Request $request)
    {
        $school_session_id = $request->session()->get('school_id');
        $subjects = Subject::leftjoin('standards', 'standards.id', '=', 'subjects.standard_id')
                            ->select('subjects.*', 'standards.standard_name', 'standards.id as standard_id')
                            ->where('standards.school_id', $school_session_id)
                            ->paginate(5);
                        $subject_subs = [];  

                        foreach ($subjects as $value) {
                            // Store sub-subjects by subject ID
                            $subject_subs[$value->id] = Subjectsub::where('subject_id', $value->id)->get();
                        }

        return view('subjects.list', compact('subjects','subject_subs'));
    }

    public function create(Request $request)
    {
        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->get();
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
        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->get();
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
            $subject->delete();
            if ($subject) {
                $newSubject = Subject::create([
                    'subject_name' => $subjectName,
                    'is_optional' => $request['is_optional'][$index], 
                    'standard_id' => $request['standard_id'],         
                    'status' => $request['status'],                   
                ]);
            
            } 
        
            // Loop through the subject sub-names and insert them
            if (!empty(array_filter($request['subject_sub_name'][$index]))) {
                foreach ($request['subject_sub_name'][$index] as $subIndex => $subjectSubName) {
                    // Fetch each specific subject sub-name by ID
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
        
        // Insert sub-subjects in bulk
                return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }
}
