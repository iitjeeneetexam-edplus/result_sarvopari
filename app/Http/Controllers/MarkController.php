<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Marks;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data=student::paginate(10);
        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->get();
        $subjects = Subject::where('subjects.is_optional',1)->get();
        // echo "<pre>";print_r($data);exit;
        return view('mark.list',compact('data','subjects','schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

        $schools = School::select('id', 'school_name')->where('id',$request->session()->get('school_id'))->get();
        return view('mark.add',compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  print_r($request->all());exit;
        //DB::beginTransaction();
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'standard_id' => 'required|exists:standards,id',
            'division_id' => 'required|exists:division,id',
            'subject_id' => 'sometimes|required_without:subject_sub',
            'subject_sub' =>'sometimes|required_without:subject_id',
            'total_marks'=>'required'
        ]);

        try{
            if ($request->input('marks') && $request->input('student_id')) {
                $marks = $request->input('marks');
                $studentIds = $request->input('student_id');
                foreach ($studentIds as $i=>$studentId) {
                    
                    if($request->input('subject_sub')){
                        $subjectid = $request->input('subject_sub');
                        $is_optional = '1';
                    }else{
                        $subjectid = $request->input('subject_id');
                        $is_optional = '0';
                    }
                    
                    Marks::Create([
                            'student_id' => $studentId,
                            'subject_id' => $subjectid,
                            'exam_id' => $request->input('exam_id'),
                            'is_optional' => $is_optional,
                            'total_marks' => $request->input('total_marks'),
                            'marks' =>  !empty($marks[$i]) ? $marks[$i] : '',
                        ]);
                   
                }                
            }
            return redirect()->route('marks.create')->with('success', 'Marks added successfully.');
            //DB::commit();
        }catch(Exception $e){
            return redirect()->route('marks.create')->with('fail', $e);
            //DB::rollBack();
            //Log::error('Marks add Error: ' . $e->getMessage());
            // return back()->with('error', 'Error during Marks add.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id,$did)
    {
       
        $student_data = Student::with('division:id,division_name') // Eager load the division with specific columns
            ->leftJoin('marks', 'marks.student_id', '=', 'students.id')
            ->leftJoin('subjects as s1', function ($join) {
                $join->on('s1.id', '=', 'marks.subject_id')
                    ->where('marks.is_optional', '0'); // Non-optional subjects
            })
            ->leftJoin('subject_subs as s2', function ($join) {
                $join->on('s2.id', '=', 'marks.subject_id')
                    ->where('marks.is_optional', '1'); // Optional subjects
            })
            ->where('students.id', $id)
            ->select(
                'students.id',
                'students.name',
                'students.roll_no',
                'students.GR_no',
                'marks.marks',
                'marks.subject_id',
                DB::raw('GROUP_CONCAT(COALESCE(s1.subject_name, s2.subject_name)) as subject_name') 
            )
            ->groupBy('students.id', 'students.name', 'students.roll_no', 'students.GR_no','marks.marks','marks.subject_id') 
            ->tosql();
        
        $standard_id = Division::where('id',$did)->pluck('standard_id');

        // $subjects = Subject::where('standard_id', $standard_id)->pluck('subject_name');
        // $subjectString = $subjects->implode(', ');
        $subjects = Subject::where('standard_id', $standard_id)->pluck('subject_name', 'id');

        // Now, create a formatted string
        $subjectString = $subjects->map(function($subjectName, $subjectId) {
            return $subjectId . ' - ' . $subjectName;  // Format as "subject_id - subject_name"
        })->implode(', '); 
        $total_marks = Subject::leftjoin('marks','marks.subject_id','=','subjects.id')
                            ->where('standard_id', $standard_id)
                            ->pluck('total_marks');
                            // print_r($subjectString);exit;
        return response()->json(['student_data'=>$student_data,'optional_subject'=>$subjectString,'total_marks'=>$total_marks]);
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $student_detail_update = Student::where('id', $id)->first();

        if ($student_detail_update) {
            $student_detail_update->update([
                'name' => $request->input('name'),
                'roll_no' => $request->input('roll_no'),
                'GR_no' => $request->input('GR_no'),
            ]);
        }
    }
   public function destroy(string $id)
    {
        //
    }
}
