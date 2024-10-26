<?php

namespace App\Http\Controllers;

use App\Models\Marks;
use App\Models\School;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('mark.list',);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $schools = School::select('id', 'school_name')->get();
        return view('mark.add',compact('schools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'standard_id' => 'required|exists:standards,id',
            'division_id' => 'required|exists:division,id',
            'subject_id' => 'sometimes|required_without:subject_sub',
            'subject_sub' =>'sometimes|required_without:subject_id',
            'total_mark'=>'required'
        ]);

        try{
            if ($request->input('marks') && $request->input('student_id')) {
                
                $marks = $request->input('marks');
                $studentIds = $request->input('student_ids');
                foreach ($studentIds as $i=>$studentId) {
                    if($request->input('subject_sub')){
                        $subjectid = $request->input('subject_sub');
                        $is_optional = 1;
                    }else{
                        $subjectid = $request->input('subject_id');
                        $is_optional = 0;
                    }
                    Marks::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'subject_id' => $subjectid,
                            'exam_id' => $request->input('exam_id'),
                            'is_optional' => $is_optional,
                            'total_marks' => $request->input('total_marks'),
                            'marks' =>  $marks[$i]
                        ]
                    );
                }                
            }
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            Log::error('Marks add Error: ' . $e->getMessage());
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
