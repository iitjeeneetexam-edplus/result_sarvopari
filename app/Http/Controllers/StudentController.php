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
        

        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->get();

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
                'students.id',
                'students.name', 'students.roll_no', 'students.GR_no','students.uid', 'students.division_id',
                DB::raw('GROUP_CONCAT(subject_subs.subject_name) as subject_name'), // Aggregate subject names
                DB::raw('GROUP_CONCAT(student_subjects.subject_id) as subject_id') // Aggregate subject IDs
            )
            ->groupBy('students.id', 'students.name', 'students.roll_no','students.GR_no','students.uid', 'students.division_id')  // Group by student ID
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

        $divisiions = Division::where('standard_id',$standardId)->get();

        return view('student.list', compact('students','subjects','subject_subs','divisiions'));
    }
    public function getstudentformarks(Request $request){

        $divisionId = $request->input('division_id');
        $standardId = $request->input('standard_id');

        $divisionId = $request->input('division_id');
        $standardId = $request->input('standard_id');

        $query = Student::with('division:id,division_name')
        ->leftJoin('marks', 'marks.student_id', '=', 'students.id')
        ->leftJoin('subjects as s1', function ($join) {
            $join->on('s1.id', '=', 'marks.subject_id')
                ->where('marks.is_optional', '0');
        })
        ->leftJoin('subject_subs as s2', function ($join) {
            $join->on('s2.id', '=', 'marks.subject_id')
                ->where('marks.is_optional', '1');
        })
        ->where('students.division_id', $divisionId)
        ->select(
            'students.id',
            'students.name',
            'students.roll_no',
            'students.GR_no',
            'marks.marks',
            'marks.subject_id',
            'marks.total_marks',
            DB::raw('GROUP_CONCAT(COALESCE(s1.subject_name, s2.subject_name)) as subject_name')
        )
        ->groupBy('students.id','students.name','students.roll_no', 'students.GR_no','marks.marks', 'marks.subject_id','marks.total_marks')
        ->get();
    $students = [];
    foreach ($query as $item) {
        $students[$item->id]['id'] = $item->id;
        $students[$item->id]['name'] = $item->name;
        $students[$item->id]['roll_no'] = $item->roll_no;
        $students[$item->id]['GR_no'] = $item->GR_no;
        $students[$item->id]['total_marks'] = $item->total_marks;
    
        $subjectName = $item->subject_name;
        $students[$item->id]['marks'][$subjectName] = $item->marks;
        }

        $subjects = Subject::where('standard_id', $standardId)->pluck('subject_name');
        $subjectString = $subjects->implode(', ');

        

        return response()->json(['student'=>$students,'subject'=>$subjectString]);
    }

    public function showImportForm(Request $request)
    {
        $schools = School::where('id',$request->session()->get('school_id'))->get();
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
                            'name' => $studentData['NAME'],
                            'roll_no' => $studentData['ROLL_NO'],
                            'GR_no' => $studentData['GR_NO'],
                            'uid' => $studentData['UID'],
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
        
        
            foreach ($studentIds as $studentId) {
                StudentSubject::where('student_id',$studentId)->delete();
                foreach ($subjectIds as $subjectId) {
                StudentSubject::create([
                    'student_id' => $studentId,
                    'subject_id' => $subjectId
                ]);
            }
        }

        return redirect()->route('students.index')->with('success', 'Subject assigned to selected students.');
        //return redirect()->back()->with('success', 'Subject assigned to selected students.');
    }

    public function StudentlistBydivisionorsubject($division_id,$subject_id){
        $studentQY = Student::with('division:id,division_name')
            ->where('division_id', $division_id)
            ->when($subject_id, function ($query) use ($subject_id) {
                $query->join('student_subjects', 'students.id', '=', 'student_subjects.student_id')
                      ->where('student_subjects.subject_id', $subject_id);
            });

        $students =  $studentQY->get();
        // $students = Student::with('division:id,division_name')
        //     ->where('division_id', $division_id)->get();
        return response()->json(['students'=>$students]);
    }

    //student details by student id
    public function editstudent($id){
        $studentdetail = student::find($id);
        $division_id = $studentdetail->division_id;
        $divid = Division::find($division_id);
        $standard_id = $divid->standard_id;
        $data = ['standard_id'=>$standard_id,'division_id'=>$division_id,'studentdetail'=>$studentdetail];
        return response()->json($data);

    }

    public function updatestudent(Request $request){
        $request->validate([
            'roll_no' => 'required|integer',
            'name' => 'required|string|max:255',
            'GR_no' => 'required|string|max:255',
            'uid' => 'required|string|max:255',
            'editDivision' => 'required|exists:division,id',
        ]);
    
        $student = Student::find($request->studentid);
    
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found');
        }
    
        $student->roll_no = $request->input('roll_no');
        $student->name = $request->input('name');
        $student->GR_no = $request->input('GR_no');
        $student->uid = $request->input('uid');
        $student->division_id = $request->input('editDivision');
    
        $student->save();

        $standard_id = Division::find($request->input('editDivision'));
        $requestdt = ['division_id'=>$request->input('editDivision'),'standard_id'=>$standard_id->standard_id];
        // return redirect()->route('students.getstudent')->with($requestdt, 'Subject assigned to selected students.');
        // return redirect()->route('students.getstudent')
        // ->with('message', 'Subject assigned to selected students.')
        // ->with('data', $requestdt);
        // $data = ['url'=>'students/getstudent/','standard_id'=>$standard_id,'division_id'=>$request->input('editDivision')];
        // return json_encode('success');
        return redirect('students/getstudent/'.$standard_id.'/'.$request->input('editDivision'));
        // return redirect()->back()->with('message', 'Student updated successfully');
    }

    public function deletestudent($id){
        $student = Student::find($id);

        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }

        $dlt = $student->delete();
        if($dlt){
            $subdl = StudentSubject::where('student_id',$id)->delete();
        }
        return response()->json($subdl);
    }

}
