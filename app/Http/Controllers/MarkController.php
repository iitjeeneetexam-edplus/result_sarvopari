<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=student::paginate(5);
        $schools = School::select('id', 'school_name')->get();
        $subjects = Subject::where('subjects.is_optional',1)->get();
        // echo "<pre>";print_r($data);exit;
        return view('mark.list',compact('data','subjects','schools'));
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
        //
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
