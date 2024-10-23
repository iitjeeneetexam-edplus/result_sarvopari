<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Standard;
use App\Models\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index()
    {
        // Fetch students with specific fields
        $students = Student::select('name', 'roll_no', 'GR_no', 'uid', 'division_id')->get();

        return view('student.list', compact('students'));
    }

    public function showImportForm()
    {
        $schools = School::all();
        return view('student.add', compact('schools'));
    }

    public function import(Request $request)
    {
        DB::beginTransaction();
        // Validate the request
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:2048',
            'school_id' => 'required|exists:schools,id',
            'standard_id' => 'required|exists:standards,id',
        ]);

        try{
            if (($handle = fopen($request->file('csv_file')->getRealPath(), 'r')) !== false) {
                // Skip the header row
                $header = fgetcsv($handle, 1000, ',');
    
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    // Map CSV data to your student fields
                    $studentData = array_combine($header, $row);
    
                    // Optionally validate student data
                    
    
                    // Create student record with school and standard information
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
