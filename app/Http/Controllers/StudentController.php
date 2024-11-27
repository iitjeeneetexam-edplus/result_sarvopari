<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Exam;
use App\Models\Marks;
use App\Models\School;
use App\Models\Siddhigun;
use App\Models\Standard;
use App\Models\Student;
use App\Models\StudentSubject;
use App\Models\Subject;
use App\Models\Subjectsub;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Performance_grace_Model;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File as FacadesFile;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        

        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->first();

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
            ->groupBy('students.id', 'students.name', 'students.roll_no','students.GR_no','students.uid', 'students.division_id') 
            ->wherenull('student_subjects.deleted_at')
            ->orderBy('students.roll_no', 'asc')
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
    public function getfinalstudent(Request $request){
        $divisionId = $request->input('division_id');
        // $standardId = $request->input('standard_id');
        $exam_id = $request->input('exam_id');
        $query = Student::leftJoin('marks', 'marks.student_id', '=', 'students.id')
        ->where('students.division_id', $divisionId)
        ->whereIn('marks.exam_id', $exam_id) 
        ->select(
            'students.id',
            'students.name',
               
        )
        ->groupBy('students.id','students.name',)
        ->orderBy('students.id','asc')
        ->get()->toarray();

        return response()->json(['student'=>$query]);
    }
    public function getstudentformarks(Request $request){
    //    print_r($request->all());exit;
        $divisionId = $request->input('division_id');
        $standardId = $request->input('standard_id');
        $exam_id = $request->input('exam_id');
        // $divisionId = $request->input('division_id');
        // $standardId = $request->input('standard_id');

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
        ->where('marks.exam_id', $exam_id) 
        ->select(
            'students.id',
            'students.name',
            'students.roll_no',
            'students.GR_no',
            'students.division_id',
            'marks.marks',
            'marks.exam_id',
            'marks.subject_id',
            'marks.is_optional',
            'marks.id as mark_id',
            
            DB::raw('GROUP_CONCAT(COALESCE(s1.subject_name, s2.subject_name)) as subject_name')
        )
        ->groupBy('students.id','students.name','students.roll_no', 'students.GR_no','marks.marks', 'marks.exam_id','marks.subject_id','marks.is_optional','marks.id','students.division_id')
        ->orderBy('students.roll_no','asc')
        ->get();

    $students = [];
    foreach ($query as $item) {
        $students[$item->id]['id'] = $item->id;
        $students[$item->id]['name'] = $item->name;
        $students[$item->id]['roll_no'] = $item->roll_no;
        $students[$item->id]['GR_no'] = $item->GR_no;
        $students[$item->id]['division_id'] = $item->division_id;
        $students[$item->id]['exam_id'] = $item->exam_id;

        $subjectName = $item->subject_name;
        $students[$item->id]['is_optional'][$subjectName] = $item->is_optional;
        $students[$item->id]['subject_id'][$subjectName] = $item->subject_id;
        
        $students[$item->id]['mark_id'][$subjectName] = $item->mark_id;
        $students[$item->id]['marks'][$subjectName] = $item->marks;
        
        
        }
         
        usort($students, function ($a, $b) {
            return $a['roll_no'] <=> $b['roll_no'];
        });
        $subjects = Subject::where('standard_id', $standardId)->pluck('subject_name');
        $subjectString = $subjects->implode(', ');

        $total_marks = Subject::leftjoin('marks','marks.subject_id','=','subjects.id')
                            ->leftjoin('subject_subs','subject_subs.subject_id','=','subjects.id')
                            ->where('standard_id', $standardId)
                            ->pluck('marks.total_marks','subjects.subject_name');
        // $total_marks = Marks::where('exam_id', $exam_id)
        //       ->select('subject_id', 'total_marks') // Select only the needed columns
        //       ->groupBy('subject_id', 'total_marks') // Group by subject_id and total_marks
        //       ->get()->toarray();

  
                            // print_r($total_marks);exit;
        return response()->json(['student'=>$students,'subject'=>$subjectString,'total_marks'=>$total_marks]);
    }

    public function showImportForm(Request $request)
    {
        $schools = School::where('id',$request->session()->get('school_id'))->first();
        return view('student.add', compact('schools'));
    }

    public function import(Request $request)
    {
        
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
            'school_id' => 'required|exists:schools,id',
            'standard_id' => 'required|exists:standards,id',
        ]);
        
        try {
            if (($handle = fopen($request->file('csv_file')->getRealPath(), 'r')) !== false) {
              
                $header = fgetcsv($handle, 1000, ',');
                $lineNumber = 1;
                $invalidRows = [];
                $processedUIDs = [];
        
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    
                    $studentData = array_combine($header, $row);
                    $missingFields = [];
                    if (in_array($studentData['UID'], $processedUIDs)) {
                        return back()->with('error', 'line ' . $lineNumber . ' message = UID already exists: ' . $studentData['UID']);
                        break;
                    }
                    $processedUIDs[] = $studentData['UID'];
                    $lineNumber++;

                    foreach (['NAME', 'ROLL_NO', 'GR_NO', 'UID'] as $field) {
                        if (empty($studentData[$field])) {
                            $missingFields[] = $field;
                        }
                    }
        
                    if (!empty($missingFields)) $invalidRows[] = ['line' => $lineNumber, 'missing_fields' => $missingFields, 'data' => $studentData];

                }
                
                fclose($handle);
       
                if (!empty($invalidRows))

                // print_r($invalidRows);exit;
                return back()->with(
                    'error',
                    '<div style="text-align: left;">' . 
                    '<b>Missing fields on line:</b> ' . $invalidRows[0]['line'] . 
                    '<br><b>Missing fields:</b> ' . implode(', ', $invalidRows[0]['missing_fields']) . 
                    '<br><b>Student data:</b><p style="text-align:left">'.json_encode($invalidRows[0]['data']).'</p></div>'
                );
                
               
                $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
                fgetcsv($handle, 1000, ','); 
                
                DB::beginTransaction(); 
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    $studentData = array_combine($header, $row);
                    $existingStudents = Student::where('uid', $studentData['UID'])
                           ->where('roll_no', $studentData['ROLL_NO'])
                           ->where('GR_no', $studentData['GR_NO'])
                           ->get(); // Get all matching students

                        if ($existingStudents->isEmpty()) { 
                           

                            // If no student exists, create a new record
                            Student::create([
                                'name' => $studentData['NAME'],
                                'roll_no' => $studentData['ROLL_NO'],
                                'GR_no' => $studentData['GR_NO'],
                                'uid' => $studentData['UID'],
                                'division_id' => $request['division_id']
                            ]);
                            
                        } else {
                            $existingNames = $existingStudents->pluck('name')->toArray();

                            $existingNames = str_replace('&quot;', '"', $existingNames); 
                            $studentName = implode(', ', $existingNames); 
                            return back()->with('error', 'Student name already exists.<br>'.$studentName.'<br>');
                            
                        }
                    }
                    
                fclose($handle);
                
                DB::commit();
                
                return redirect()->back()->with('success', 'Students imported successfully!');
            }
        
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('CSV Import Error: ' . $e->getMessage());
            return back()->with('error', 'Error during CSV import.');
        }
        

       
    }

    public function assignSubject(Request $request)
    {
    //    echo"<pre>"; print_r($request->all());exit;
        $subjectIds = $request->input('subject_ids');
        $studentIds = $request->input('student_ids');
        
        
        foreach ($studentIds as $studentId) {
            $validSubjectIds = array_filter($subjectIds);
            
            foreach ($validSubjectIds as $subjectId) {
                $msubjectid = Subjectsub::where('id',$subjectId)->select('subject_id')->first();
                $subjecs = Subjectsub::where('subject_id',$msubjectid->subject_id)->pluck('id');
                $stdDT = StudentSubject::where('student_id',$studentId)->whereIN('subject_id',$subjecs)->first();
                if($stdDT){
                    StudentSubject::where('id',$stdDT->id)->update(
                        [
                            'student_id' => $studentId,  
                            'subject_id' => $subjectId   
                        ],
                    );
                }else{
                    StudentSubject::Create(
                        [
                            'student_id' => $studentId,  
                            'subject_id' => $subjectId   
                        ],
                    );
                }
                    
           
            }
        }
        
        
        
        return redirect()->route('students.index')->with('success', 'Subject assigned to selected students.');
        //return redirect()->back()->with('success', 'Subject assigned to selected students.');
    }

    public function StudentlistBydivisionorsubject($division_id,$subject_id,$is_optional,$exam_id){
        $studentQY = Student::with('division:id,division_name')
        ->leftJoin('marks', function ($join) use ($subject_id, $is_optional, $exam_id) {
            $join->on('marks.student_id', '=', 'students.id')
                ->where('marks.subject_id', $subject_id)
                ->where('marks.is_optional', $is_optional)
                ->where('marks.exam_id', $exam_id);
        })
            ->select('students.id', 'students.name', 'students.roll_no','marks.marks','marks.total_marks','marks.passing_marks')
            ->orderBy('students.roll_no','asc')
            ->where('division_id', $division_id);

            if($is_optional == 1){
                $studentQY = $studentQY->when($subject_id, function ($query) use ($subject_id,$is_optional,$exam_id) {
                    $query->join('student_subjects', 'students.id', '=', 'student_subjects.student_id')                    
                    ->where('student_subjects.subject_id', $subject_id);
                });
            }
            
        $students =  $studentQY->get();
        return response()->json(['students'=>$students]);
    }

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
        if (is_string($id)) {
            $id = explode(',', $id); 
        }
        $student = Student::whereIn('id', $id);
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found.');
        }
        $dlt = $student->delete();
        if($dlt){
            $subdl = StudentSubject::whereIn('student_id',$id)->delete();
            $subdl = Marks::whereIn('student_id', $id)->delete();
        }
        return response()->json($subdl);
    }
    public function sidhi_gun(Request $request,$id){
        $studentDta=Student::leftjoin('division','division.id','=','students.division_id')
                            ->leftjoin('standards','standards.id','=','division.standard_id')
                            ->leftjoin('schools','schools.id','=','standards.school_id')
                             ->where('students.id',$id)
                             ->select('students.*',
                                'standards.standard_name',
                                'standards.id as standard_id',
                                'schools.school_name',
                                'schools.medium',
                                'standards.school_index',
                                'schools.address',
                                'division.division_name',
                            )->get();
                            $data=[];
                    foreach($studentDta as $value){
                        $examDta = Exam::whereIn('standard_id', explode(',',$value['standard_id']))->get();
                        $exam= [];

                        foreach ($examDta as $exam_value) {
                            $subjectDta1 = Student::leftJoin('division', 'division.id', '=', 'students.division_id')
                                                    ->leftJoin('standards', 'standards.id', '=', 'division.standard_id')
                                                    ->leftJoin('subjects', 'subjects.standard_id', '=', 'standards.id')
                                                    ->where('students.id', explode(',', $value->id))
                                                    ->where('subjects.is_optional','0')
                                                    ->select(
                                                        'subjects.subject_name',
                                                        'subjects.is_optional',
                                                        'subjects.id as subject_id',
                                                        'students.id AS student_id'
                                                    )
                                                    ->get()
                                                    ->toArray(); 

                                                $subjectDta2 = Subject::leftJoin('subject_subs', 'subject_subs.subject_id', '=', 'subjects.id')
                                                    ->leftJoin('student_subjects', 'student_subjects.subject_id', '=', 'subject_subs.id')
                                                    ->where('subjects.is_optional','1')
                                                    ->where('student_subjects.student_id', $value->id)
                                                    ->select(
                                                        'subject_subs.subject_name',
                                                        'subjects.is_optional',
                                                        'subject_subs.id as subject_id',
                                                        'student_subjects.student_id AS student_id'
                                                    )
                                                    ->get()
                                                    ->toArray(); 

                                                $get_subject_Data = array_merge($subjectDta1, $subjectDta2);
                                        $subject_Data =[];    
                                    foreach ($get_subject_Data as $subject_value) {
                                        
                                        $markDta = Student::leftJoin('marks', 'marks.student_id', '=', 'students.id')
                                            ->whereIn('marks.exam_id', explode(',', $exam_value->id))
                                            ->where('students.id', $id)
                                            ->where('marks.subject_id', $subject_value['subject_id'])
                                            ->where('marks.is_optional', $subject_value['is_optional'])
                                            ->select(
                                                'marks.student_id',
                                                'marks.total_marks',
                                                'marks.passing_marks',
                                                'marks.marks',
                                                'marks.subject_id'
                                            )
                                            ->get();
                                
                                        $marks = []; 
                                
                                        foreach ($markDta as $value2) {
                                            $value2->marks = $value2->marks !== 'AB' 
                                                ? (int) round((float) $value2->marks) 
                                                : 'AB';
                                
                                            $marks[] = [
                                                'student_id' => $value2->student_id,
                                                'subject_id' => $value2->subject_id,
                                                'total_marks' => $value2->total_marks,
                                                'marks' => $value2->marks,
                                                'exam_id' => $exam_value->id,
                                                'passing_marks' => $value2->passing_marks,
                                            ];
                                        }
                                
                                        $subject_Data[] = [
                                            'subject_name' => $subject_value['subject_name'],
                                            'subject_id' => $subject_value['subject_id'],
                                            'is_optional'=> $subject_value['is_optional'],
                                            'marks' => $marks,
                                        ];
                                    }
                                
                                    $exam[] = [
                                        'exam_id' => $exam_value->id,
                                        'exam_name' => $exam_value->exam_name,
                                        'exam_year' => $exam_value->exam_year,
                                        'result_date' => $exam_value->result_date,
                                        'subject_Data' => $subject_Data,
                                    ];
                                }
                                $getpergracmark = Performance_grace_Model::where('school_id',$request->session()->get('school_id'))->first();
                                $data[]=[
                                    'id'=>$value->id,
                                    'student_name'=>$value->name,
                                    'roll_no'=>$value->roll_no,
                                    'gr_no'=>$value->GR_no,
                                    'uid'=>$value->uid,
                                    'school_index'=>$value->school_index,
                                    'medium'=>$value->medium,
                                    'division_name'=>$value->division_name,
                                    'address'=>$value->address,
                                    'standard_id'=>$value->standard_id,
                                    'standard_name'=>$value->standard_name,
                                    'school_name'=>$value->school_name,
                                    'medium'=>$value->medium,
                                    'address'=>$value->address,
                                    'division_name'=>$value->division_name,
                                    'performance_mark'=>$getpergracmark->performance,
                                    'grace_mark'=>$getpergracmark->grace,
                                    'exam'=>$exam,
                                ];
                                    
                            }
       return view('mark.sidhi_gun', compact('data'));
    }

    public function siddhi_gunstore(Request $request){
        foreach($request->subject_id as $is=>$subjects){
            $meksid = Marks::where('student_id',$request->student_id)
            ->where('subject_id',$subjects)
            ->where('exam_id',$request->exam_id)
            ->where('is_optional',$request->is_optional[$is])->first();
            if($meksid && $request->performance_mark[$is]){
            Marks::where('id',$meksid->id)->update([
                'performance_mark'=>$request->performance_mark[$is],
                'grace_mark'=>$request->grace[$is],
            ]);
        }
        }
        
        
    }
    public function performance_grace(Request $request){
        $performance=Performance_grace_Model::first();
        // echo "<pre>";print_r($performance);exit;
        return view('mark.performance_grace',compact('performance'));
    }
    public function performance_grace_add(Request $request){
       
        if (!empty($request['id'])) {
            $performanceGrace = Performance_grace_Model::findOrFail($request['id']);
            $performanceGrace->update([
                'school_id'=>$request->session()->get('school_id'),
                'performance' => $request['performance'],
                'grace' => $request['grace'],
            ]);
    
            $message = 'Record updated successfully.';
            return back()->with('success', $message);
        } else {
            Performance_grace_Model::create([
                'school_id'=>$request->session()->get('school_id'),
                'performance' => $request['performance'],
                'grace' => $request['grace'],

            ]);
    
            $message = 'Record added successfully.';
            return back()->with('success', $message);
        }
    
       
    }
    public function all_marksheet(Request $request){
        $studentDta=Student::leftjoin('division','division.id','=','students.division_id')
                            ->leftjoin('standards','standards.id','=','division.standard_id')
                            ->leftjoin('schools','schools.id','=','standards.school_id')
                             ->whereIn('students.id',$request->student_id)
                             ->select('students.*',
                                'standards.standard_name',
                                'standards.id as standard_id',
                                'schools.school_name',
                                'schools.medium',
                                'standards.school_index',
                                'schools.address',
                                'division.division_name',
                            )->get();
                            $data=[];
                    foreach($studentDta as $value){
                        $examDta = Exam::whereIn('id', explode(',', $request->exam))->get();
                        $exam= [];

                        foreach ($examDta as $exam_value) {
                            $subjectDta1 = Student::leftJoin('division', 'division.id', '=', 'students.division_id')
                                                    ->leftJoin('standards', 'standards.id', '=', 'division.standard_id')
                                                    ->leftJoin('subjects', 'subjects.standard_id', '=', 'standards.id')
                                                    ->whereIn('students.id', explode(',', $value->id))
                                                    ->where('subjects.is_optional','0')
                                                    ->select(
                                                        'subjects.subject_name',
                                                        'subjects.is_optional',
                                                        'subjects.id as subject_id',
                                                        'students.id AS student_id'
                                                    )
                                                    ->get()
                                                    ->toArray(); 

                                                $subjectDta2 = Subject::leftJoin('subject_subs', 'subject_subs.subject_id', '=', 'subjects.id')
                                                    ->leftJoin('student_subjects', 'student_subjects.subject_id', '=', 'subject_subs.id')
                                                    ->where('subjects.is_optional','1')
                                                    ->whereIn('student_subjects.student_id', explode(',', $value->id))
                                                    ->select(
                                                        'subject_subs.subject_name',
                                                        'subjects.is_optional',
                                                        'subject_subs.id as subject_id',
                                                        'student_subjects.student_id AS student_id'
                                                    )
                                                    ->get()
                                                    ->toArray(); 

                                                $get_subject_Data = array_merge($subjectDta1, $subjectDta2);
                                        $subject_Data =[];    
                                    foreach ($get_subject_Data as $subject_value) {
                                        
                                        $markDta = Student::leftJoin('marks', 'marks.student_id', '=', 'students.id')
                                            ->whereIn('marks.exam_id', explode(',', $exam_value->id))
                                            ->whereIn('students.id', explode(',', $value->id))
                                            ->where('marks.subject_id', $subject_value['subject_id'])
                                            ->where('marks.is_optional', $subject_value['is_optional'])
                                            ->select(
                                                'marks.student_id',
                                                'marks.total_marks',
                                                'marks.marks',
                                                'marks.subject_id',
                                                'marks.performance_mark',
                                                'marks.grace_mark',
                                            )
                                            ->get();
                                
                                        $marks = []; 
                                
                                        foreach ($markDta as $value2) {
                                            $value2->marks = $value2->marks !== 'AB' 
                                                ? (int) round((float) $value2->marks) 
                                                : 'AB';
                                
                                            $marks[] = [
                                                'student_id' => $value2->student_id,
                                                'subject_id' => $value2->subject_id,
                                                'total_marks' => $value2->total_marks,
                                                'marks' => $value2->marks,
                                                'exam_id' => $exam_value->id,
                                                'performance_mark' => $exam_value->performance_mark,
                                                'grace_mark' => $exam_value->grace_mark,
                                            ];
                                        }
                                
                                        $subject_Data[] = [
                                            'subject_name' => $subject_value['subject_name'],
                                            'subject_id' => $subject_value['subject_id'],
                                            'marks' => $marks,
                                        ];
                                    }
                                
                                    $exam[] = [
                                        'exam_id' => $exam_value->id,
                                        'exam_name' => $exam_value->exam_name,
                                        'exam_year' => $exam_value->exam_year,
                                        'result_date' => $exam_value->result_date,
                                        'subject_Data' => $subject_Data,
                                    ];
                                }
                                
                                $data[]=[
                                    'id'=>$value->id,
                                    'student_name'=>$value->name,
                                    'roll_no'=>$value->roll_no,
                                    'gr_no'=>$value->GR_no,
                                    'uid'=>$value->uid,
                                    'school_index'=>$value->school_index,
                                    'medium'=>$value->medium,
                                    'division_name'=>$value->division_name,
                                    'address'=>$value->address,
                                    'standard_id'=>$value->standard_id,
                                    'standard_name'=>$value->standard_name,
                                    'school_name'=>$value->school_name,
                                    'medium'=>$value->medium,
                                    'school_index'=>$value->school_index,
                                    'address'=>$value->address,
                                    'division_name'=>$value->division_name,
                                    'exam'=>$exam,
                                ];
                                    
                            }
                            // echo "<pre>";print_r($data);exit;
                            $baseWidth = 580.28; // A4 width in points (8.27 inches at 72 dpi)
        $additionalWidth = 50; // Additional width per subject
        $totalWidth = $baseWidth + max(0, (6 - 5) * $additionalWidth);
            $pdf = PDF::loadView('mark.viewfinalmarksheet', ['student' => $data])->setPaper([0, 0, $totalWidth, 841.89]);
            // return $pdf->download('marksheet.pdf');
            $folderPath = public_path('pdfs');

            if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
            }

            $baseFileName = 'marksheet.pdf';
            $pdfPath = $folderPath . '/' . $baseFileName;

            $counter = 1;
            while (File::exists($pdfPath)) {
            $pdfPath = $folderPath . '/marksheet' . $counter . '.pdf'; 
            $counter++;
            }

            file_put_contents($pdfPath, $pdf->output());
            $pdfUrl = asset('pdfs/' . basename($pdfPath));
            return response()->json(['pdfUrl'=>$pdfUrl]);

       
        // echo "<pre>";print_r($data);exit;
    }
    public function marksheet(Request $request){
        // print_r($request->all());exit;
        try{ 
            $student=Student::leftjoin('division','division.id','=','students.division_id')
            ->leftjoin('standards','standards.id','=','division.standard_id')
            ->leftjoin('exams','exams.standard_id','=','standards.id')
            ->leftjoin('schools','schools.id','=','standards.school_id')
            ->select('students.*',
                      'standards.standard_name',
                      'standards.id as standard_id',
                      'schools.school_name',
                      'schools.medium',
                      'standards.school_index',
                      'schools.address',
                      'division.division_name',
                      'exams.exam_name',
                      'exams.exam_year',
                      'exams.result_date'
                    )
            ->whereIn('students.id',$request->student_id)
            ->where('exams.id',$request->exam)
            ->get()
            ->toarray();
            $standard_id=0;
            if(!empty($student))
            {
             $standard_id=$student[0]['standard_id'];
            }
            $subjectsData = Subject::leftJoin('marks', function ($join) {
                $join->on('marks.subject_id', '=', 'subjects.id')
                     ->where('marks.is_optional', '0');
            })
            ->where('subjects.standard_id', $standard_id)
            ->whereIn('marks.student_id', $request->student_id)
            ->where('marks.exam_id', $request->exam)
            ->select(
                'subjects.subject_name',
                'subjects.id',
                'subjects.is_optional',
                'marks.total_marks',
                'marks.marks',
                'marks.passing_marks',
                'marks.subject_id as mark_subject_id',
                'marks.is_optional as mark_is_optional',
                'marks.student_id',
            )
            ->get()->toarray();
            $optinalsubjects = Subject::join('subject_subs','subject_subs.subject_id','=','subjects.id')
            ->leftJoin('marks', function ($join) {
                $join->on('marks.subject_id', '=', 'subject_subs.id')
                     ->where('marks.is_optional', '1');
            })
            ->where('subjects.standard_id', $standard_id)
            ->where('marks.exam_id', $request->exam)
            ->whereIn('marks.student_id', $request->student_id)
            ->select(
                'subject_subs.subject_name',
                'subject_subs.id',
                'subjects.is_optional',
                'marks.total_marks',
                'marks.marks',
                'marks.subject_id as mark_subject_id',
                'marks.is_optional as mark_is_optional',
                'marks.passing_marks',
                'marks.student_id',

            )
            ->get()->toarray(); 
            $optinalsubjects = array_map(function($row) {
                if ($row["marks"] !== 'AB') {
                    $row["marks"] = (int) round((float) $row["marks"]);
                }
                return $row;
            }, $optinalsubjects);
            
            $subjectsData = array_map(function($row) {
                if ($row["marks"] !== 'AB') {
                    $row["marks"] = (int) round((float) $row["marks"]);
                }
                return $row;
            }, $subjectsData);
            
            // $response_data = [
            //     'student' => $student,
            //     'subjects' => $subjectsData,
            //     'optional_subjects'=>$optinalsubjects,
            // ];
            // exit;
            // echo "<pre>";print_r($response_data);exit;
            $tempStudantId=array_column($subjectsData, 'student_id') ;
            $tempOpsStudantId =array_column($optinalsubjects, 'student_id');
            $tempMerge=array_unique(array_merge($tempStudantId, $tempOpsStudantId));  
            $filteredStudents = array_filter($student, function ($st) use ($tempMerge) {  
               return is_array($tempMerge) && isset($st['id']) && in_array($st['id'], $tempMerge);
            });
            $data = ['student'=>$filteredStudents,'subjects'=>$subjectsData,'optional_subjects'=>$optinalsubjects]; 
            // echo "<pre>";print_r($data);exit;
            $pdf = PDF::loadView('mark.marksheet', ['data' => $data]);
            // return $pdf->download('marksheet.pdf');
            $folderPath = public_path('pdfs');

            if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
            }

            $baseFileName = 'marksheet.pdf';
            $pdfPath = $folderPath . '/' . $baseFileName;

            $counter = 1;
            while (File::exists($pdfPath)) {
            $pdfPath = $folderPath . '/marksheet' . $counter . '.pdf'; 
            $counter++;
            }

            file_put_contents($pdfPath, $pdf->output());
            $pdfUrl = asset('pdfs/' . basename($pdfPath));
            return response()->json(['pdfUrl'=>$pdfUrl]);

        }catch(Exception $e){
            return redirect()->route('marks.index')->with([], "Something went wrong!.", false, 400);
        }
    }
    public function generatePDF(Request $request)
    {
        $students = $request->input('students');
        $subjects = $request->input('subjects');
        $baseWidth = 595.28; // A4 width in points (8.27 inches at 72 dpi)
        $additionalWidth = 50; // Additional width per subject
        $totalWidth = $baseWidth + max(0, (count($subjects) - 5) * $additionalWidth);

        // Create PDF with custom size
        $pdf = PDF::loadView('mark.studentdatapdf', ['students' => $students, 'subjects' => $subjects])
                ->setPaper([0, 0, $totalWidth, 841.89]); // Height is A4 in points (11.69 inches)

        // Save PDF in public/pdfs with a unique filename if it exists
        $folderPath = public_path('pdfs');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }

        $baseFileName = 'Markpdf.pdf';
        $pdfPath = $folderPath . '/' . $baseFileName;
        $counter = 1;
        while (File::exists($pdfPath)) {
            $pdfPath = $folderPath . '/Markpdf' . $counter . '.pdf';
            $counter++;
        }

        file_put_contents($pdfPath, $pdf->output());

        // Return PDF URL
        $pdfUrl = asset('pdfs/' . basename($pdfPath));
        return response()->json(['pdfUrl' => $pdfUrl]);
       }

    public function subjectmarksPDF(Request $request){
        $schoolname = School::where('id',$request->school_id)->select('school_name')->first();
        $standardname = Standard::where('id',$request->standard_id)->select('standard_name')->first();
        $divisionname = Division::where('id',$request->division_id)->select('division_name')->first();
        $total_marks = $request->total_marks;
        $passing_marks = $request->passing_marks;

        if($request->subject_sub){
            $subjectname = Subjectsub::where('id',$request->subject_sub)->select('subject_name')->first();
        }else{
            $subjectname = Subject::where('id',$request->subject_id)->select('subject_name')->first();

        }

        foreach ($request->student_id as $i=>$studentId) {                
            $studentname = Student::where('id',$studentId)->select('name','roll_no')->first();
                    $students[] = [
                        'name'=>$studentname->name,
                        'roll_no'=>$studentname->roll_no,
                        'marks' => !empty($request->marks[$i]) ? ceil($request->marks[$i]) : '',
                    ];
                
        }
        $pdf = PDF::loadView('mark.subjectsmarks', ['school_name'=>$schoolname->school_name,
            'standard_name'=>$standardname->standard_name,
            'division_name'=>$divisionname->division_name,
            'total_marks'=>$request->total_marks,
            'passing_marks'=>$request->passing_marks,
            'subject_name'=>$subjectname->subject_name,
            'students'=>$students
        ]);

            $folderPath = public_path('pdfs');
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }
    
            $baseFileName = 'subjectsmarks.pdf';
            $pdfPath = $folderPath . '/' . $baseFileName;
            $counter = 1;
            while (File::exists($pdfPath)) {
                $pdfPath = $folderPath . '/subjectsmarks' . $counter . '.pdf';
                $counter++;
            }
    
            file_put_contents($pdfPath, $pdf->output());
    
            // Return PDF URL
            $pdfUrl = asset('pdfs/' . basename($pdfPath));
            return response()->json(['pdfUrl' => $pdfUrl]);

    }
    public function final_marksheet(Request $request){
        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->first();
        return view('mark.finalmarksheet', compact('schools'));
    }
}
