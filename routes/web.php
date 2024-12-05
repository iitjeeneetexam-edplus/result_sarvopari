<?php

use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\StudentController;
use App\Models\Division;
use App\Models\Exam;
use App\Models\School;
use App\Models\Standard;
use App\Models\Student;
use App\Models\Subject;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        return redirect('/schools');
    })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



Route::get('/schools', [SchoolController::class, 'index'])->name('schools');
Route::get('/schools/create', [SchoolController::class, 'create'])->name('school.create');
Route::post('/schools/store', [SchoolController::class, 'store'])->name('school.store');
Route::get('/schools/edit/{school_id}', [SchoolController::class, 'edit'])->name('school.edit');
Route::post('/schools/update', [SchoolController::class, 'update'])->name('school.update');
Route::get('/schools/delete/{school_id}', [SchoolController::class, 'delete'])->name('school.delete');
Route::get('/schools/view/{school_id}', [SchoolController::class, 'view'])->name('school.view');



Route::get('/standards', [StandardController::class, 'index'])->name('standards.index');
Route::get('/standards/create', [StandardController::class, 'create'])->name('standards.create');
Route::post('/standards/store', [StandardController::class, 'store'])->name('standards.store');
Route::get('/standards/edit/{standard_id}', [StandardController::class, 'edit'])->name('standards.edit');
Route::post('/standards/update', [StandardController::class, 'update'])->name('standards.update');
Route::get('/standards/delete/{standard_id}', [StandardController::class, 'delete'])->name('standards.delete');


Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
Route::get('/subjects/create', [SubjectController::class, 'create'])->name('subjects.create');
Route::post('/subjects/store', [SubjectController::class, 'store'])->name('subjects.store');
Route::get('/subjects/edit/{subject_id}', [SubjectController::class, 'edit'])->name('subjects.edit');
Route::post('/subjects/update', [SubjectController::class, 'update'])->name('subjects.update');
Route::get('/subjects/delete/{subject_id}', [SubjectController::class, 'delete'])->name('subjects.delete');

Route::get('/division', [DivisionController::class, 'index'])->name('division.index');
Route::get('/division/create', [DivisionController::class, 'create'])->name('division.create');
Route::post('/division/store', [DivisionController::class, 'store'])->name('division.store');
Route::get('/division/edit/{division_id}', [DivisionController::class, 'edit'])->name('division.edit');
Route::post('/division/update', [DivisionController::class, 'update'])->name('division.update');
Route::get('/division/delete/{division_id}', [DivisionController::class, 'delete'])->name('division.delete');


Route::get('/exam', [ExamController::class, 'index'])->name('exam.index');
Route::get('/exam/create', [ExamController::class, 'create'])->name('exam.create');
Route::post('/exam/store', [ExamController::class, 'store'])->name('exam.store');
Route::get('/exam/edit/{division_id}', [ExamController::class, 'edit'])->name('exam.edit');
Route::post('/exam/update', [ExamController::class, 'update'])->name('exam.update');
Route::get('/exam/delete/{division_id}', [ExamController::class, 'delete'])->name('exam.delete');


Route::get('/get-standards/{school_id}', [StandardController::class, 'getStandardsBySchool'])->name('get-standards');
Route::get('/get-subjects-sub/{subject_id}', [StandardController::class, 'getSubjectsubBySchool'])->name('get-subjects-sub');
Route::get('/get-divisions-subject/{standard_id}', [StandardController::class, 'getdivisionBydivisionsubject'])->name('get-divisions-subject');

Route::get('/get-divisions/{standard_id}', [DivisionController::class, 'getdivisionBystandard'])->name('get-divisions');
Route::get('/get-exam/{standard_id}', [DivisionController::class, 'getstandardByexam'])->name('get-exam');


Route::get('/students', [StudentController::class, 'index'])->name('students.index');
// Route::post('/students/getstudent', [StudentController::class, 'getstudents'])->name('students.getstudent');
Route::get('/students/getstudent/{sid}/{did}', [StudentController::class, 'getstudents'])->name('students.getstudent');
Route::get('/students/getstudent', [StudentController::class, 'getstudents'])->name('students.getstudent.get');
Route::post('/students/getstudentformarks', [StudentController::class, 'getstudentformarks'])->name('students.getstudentformarks');
Route::post('/students/getfinalstudent', [StudentController::class, 'getfinalstudent'])->name('students.getfinalstudent');
Route::get('/students/add', [StudentController::class, 'showImportForm'])->name('students.importForm');
Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');

Route::get('/students/marksaddstudentlist/{division_id}/{subject_id}/{is_optional}/{exam_id}', [StudentController::class, 'StudentlistBydivisionorsubject'])->name('students.marksaddstudentlist');
Route::get('/marks', [MarkController::class, 'index'])->name('marks.index');
Route::get('/marks/create', [MarkController::class, 'create'])->name('marks.create');
Route::post('/marks/store', [MarkController::class, 'store'])->name('marks.store');
Route::get('/marks/edit/{sid}/{did}', [MarkController::class, 'edit'])->name('marks.edit');
Route::post('/marks/update', [MarkController::class, 'update'])->name('marks.update');
Route::get('/marks/delete/{sid}', [MarkController::class, 'destroy'])->name('marks.destroy');

Route::post('/assign-subject', [StudentController::class, 'assignSubject'])->name('assign.subject');

Route::get('/students/edit/{id}', [StudentController::class, 'editstudent'])->name('students.edit');
Route::put('/students/update', [StudentController::class, 'updatestudent'])->name('students.edit');
Route::get('/students/delete/{id}', [StudentController::class, 'deletestudent'])->name('students.delete');

Route::post('/student/marksheet', [StudentController::class, 'marksheet'])->name('students.marksheet');
Route::post('/student/final-marksheet', [StudentController::class, 'all_marksheet'])->name('students.final_marksheet');
Route::post('/student/final-marksheet-guj', [StudentController::class, 'all_marksheet_guj'])->name('students.final_marksheet_guj');

Route::post('/generate-pdf', [StudentController::class, 'generatePDF'])->name('allgenerate.pdf');
Route::post('/subjectmarks-pdf', [StudentController::class, 'subjectmarksPDF'])->name('generate.pdf');
Route::get('/marksheet', [StudentController::class, 'final_marksheet'])->name('marksheet');
Route::post('/marksheet/sidhi_gun', [StudentController::class, 'sidhi_gun'])->name('sidhi_gun');
Route::post('/siddhi_gun/store', [StudentController::class, 'siddhi_gunstore'])->name('siddhi_gun.store');
Route::get('/performance-grace', [StudentController::class, 'performance_grace'])->name('performance_grace');
Route::post('/performance-grace-add', [StudentController::class, 'performance_grace_add'])->name('performance_grace_add');


});
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

require __DIR__.'/auth.php';
