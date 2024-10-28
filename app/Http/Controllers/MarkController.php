<?php

namespace App\Http\Controllers;

use App\Models\Marks;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data=student::paginate(5);
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
                            'marks' =>  $marks[$i]
                        ]);
                   
                }                
            }
            return redirect()->route('marks.create')->with('success', 'Marks added successfully.');
            //DB::commit();
        }catch(Exception $e){
            return redirect()->route('marks.create')->with('fail', $e);
            //DB::rollBack();
            //Log::error('Marks add Error: ' . $e->getMessage());
            return back()->with('error', 'Error during Marks add.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
