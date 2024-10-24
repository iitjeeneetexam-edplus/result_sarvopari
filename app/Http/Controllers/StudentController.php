<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\School;
use App\Models\Standard;
use App\Models\Student;
use App\Models\Subject;
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

        $query = Student::with('division:id,division_name')
        ->where('division_id', $divisionId)
        ->select('name', 'roll_no', 'GR_no', 'uid', 'division_id');
        $students = $query->get();

        $subjects = Subject::join('subject_subs','subject_subs.subject_id','=','subjects.id')
        ->where('subjects.standard_id',$standardId)
        ->where('subjects.is_optional',1)
        ->get();

        return view('student.list', compact('students','subjects'));
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
}
