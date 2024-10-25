<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\School;
use App\Models\Standard;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use App\Models\Subjectsub;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        

        $schools = School::select('id', 'school_name')->get();

        return view('student.listfilter', compact('schools'));
    }

    public function getstudents(Request $request)
    {
        $divisionId = $request->input('division_id');
        $standardId = $request->input('standard_id');

        $divisionId = $request->input('division_id');
        $standardId = $request->input('standard_id');

        $query = Student::with('division:id,division_name')
            ->leftJoin('student_subjects', 'students.id', '=', 'student_subjects.student_id')
            ->leftJoin('subject_subs', 'subject_subs.id', '=', 'student_subjects.subject_id')
            ->where('division_id', $divisionId)
            ->select(
                'students.*',
                DB::raw('GROUP_CONCAT(subject_subs.subject_name) as subject_name'), // Aggregate subject names
                DB::raw('GROUP_CONCAT(student_subjects.subject_id) as subject_id') // Aggregate subject IDs
            )
            ->groupBy('students.id')  // Group by student ID
            ->get();

        $students = $query;
        // echo "<pre>";print_r($students);exit;
        // $student_id=Student::join('student_subjects','student_subjects.student_id','=','students.id')
        //                     ->leftjoin('subject_subs','subject_subs.subject_id','=','student_subjects.subject_id')
        //                   ->select('students.*','student_subjects.subject_id','subject_subs.subject_name')->get();
        // echo "<pre>";print_r($student_id);exit;
        $subjects = Subject::where('subjects.standard_id',$standardId)
         ->where('subjects.is_optional',1)->get();
        $subject_subs = [];  
        foreach($subjects as $value){
            $subject_subs[$value->id] = Subjectsub::where('subject_id', $value->id)->get(); // Store sub-subjects by subject ID
        }
        return view('student.list', compact('students','subjects','subject_subs'));
    }

    public function showImportForm()
    {
        $schools = School::all();
        return view('student.add', compact('schools'));
    }

    public function import(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
            'school_id' => 'required|exists:schools,id',
            'standard_id' => 'required|exists:standards,id',
        ]);

        try{
            if (($handle = fopen($request->file('csv_file')->getRealPath(), 'r')) !== false) {
                $header = fgetcsv($handle, 1000, ',');
    
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    $studentData = array_combine($header, $row);
                    Student::updateOrCreate(
                        [
                            'name' => $studentData['name'],
                            'roll_no' => $studentData['roll_no'],
                            'GR_no' => $studentData['GR_no'],
                            'uid' => $studentData['uid'],
                            'division_id' => $request['division_id']
                        ]
                    );
                }
    
                fclose($handle);
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            Log::error('CSV Import Error: ' . $e->getMessage());
            return back()->with('error', 'Error during CSV import.');
        }
        // Read the CSV file
        

        return redirect()->back()->with('success', 'Students imported successfully!');
    }

    public function assignSubject(Request $request)
    {
        $subjectIds = $request->input('subject_ids');
        $studentIds = $request->input('student_ids');
        
        foreach ($subjectIds as $subjectId) {
            foreach ($studentIds as $studentId) {
                
                StudentSubject::create([
                    'student_id' => $studentId,
                    'subject_id' => $subjectId
                ]);
            }
        }

        return redirect()->route('students.index')->with('success', 'Subject assigned to selected students.');
        //return redirect()->back()->with('success', 'Subject assigned to selected students.');
    }

}
