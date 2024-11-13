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
use App\Models\User;
use Illuminate\Support\Facades\Auth;;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class SchoolController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id; 
        $schools = School::where("user_id",$userId)->paginate(50); 
        return view('school.list', compact('schools'));
    }

    public function create()
    { 
        return view('school.add'); 
    }

    public function store(Request $request)
    { 
        $request->validate([
            'school_name' => 'required|string|max:255|unique:schools',
            'address' => 'required|string',
            'email' => 'required|email|unique:schools',
            'contact_no' => 'required|regex:/[0-9]{10}/',
            'status' => 'required|in:1,0', 
        ]);
        $user = Auth::user();
        $userId = $user->id;  
        // Create a new school record
        School::create([
            'school_name' => $request->school_name,
            'medium' => $request->medium,
            'address' => $request->address,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'status' => $request->status,
            'user_id' => $userId,

        ]);

        
        return redirect()->route('schools')->with('success', 'School added successfully!');
    }
    public function edit(Request $request,$id){
         $users = User::all();
         $data=School::where('id',$id)->first();
         return view('school.edit', compact('data','users'));
         
    }
    public function update(Request $request){
        $school = School::findOrFail($request->id);

        // Validate the request data, allowing the current school's email and name to be unique to itself
        $request->validate([
            'school_name' => 'required|string|max:255|unique:schools,school_name,' . $request->id,
            'address' => 'required|string',
            'email' => 'required|email|unique:schools,email,' . $request->id,
            'contact_no' => 'required|regex:/[0-9]{10}/',
            'status' => 'required|in:1,0', 
        ]);
    
        // Update the school record
        $school->update([
            'school_name' => $request->school_name,
            'medium' => $request->medium,
            'address' => $request->address,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
            'status' => $request->status, 
        ]);
    
        // Optionally, return a response or redirect
        return redirect()->route('schools')->with('success', 'School updated successfully.');
    
    }
    
    public function delete(Request $request,$id){
        $school = School::findOrFail($id);
        $school->delete();

        $standard_ids = Standard::where('school_id', $id)->pluck('id')->toArray();
       if (!empty($standard_ids)) {
            Standard::whereIn('id', $standard_ids)->delete();
           
            $subject_ids = Subject::whereIn('standard_id', $standard_ids)->pluck('id')->toArray();
            
            if (!empty($subject_ids)) {
                Subject::whereIn('id', $subject_ids)->delete();
                Subjectsub::whereIn('subject_id', $subject_ids)->delete();
            }

            $division_ids = Division::whereIn('standard_id', $standard_ids)->pluck('id')->toArray();

            if (!empty($division_ids)) {
                Division::whereIn('id', $division_ids)->delete();

                Exam::whereIn('standard_id', $standard_ids)->delete();

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
        }
        return redirect()->route('schools')->with('success', 'School deleted successfully.');
    
    }
    public function view($id,Request $request){
        $standards = Standard::with('school')
            ->whereHas('school', function($query) use ($id) {
                $query->where('id', $id);
            })
        ->paginate(5);
       //set session
        $request->session()->put('school_id', $id);
       
       
        $value = $request->session()->get('school_id');
       
        return view('standard.list', compact('standards')); 
    }
}
