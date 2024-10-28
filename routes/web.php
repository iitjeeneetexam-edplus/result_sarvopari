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

Route::get('/dashboard', function () {
    $school_count=School::count();
    $Standard_count=Standard::count();
    $Subject_count=Subject::count();
    $Division_count=Division::count();
    $Exam_count=Exam::count();
    $Student_count=Student::count();
    return view('dashboard',compact('school_count','Standard_count','Subject_count','Division_count','Exam_count','Student_count'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
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

Route::get('/students', [StudentController::class, 'index'])->name('students.index');
// Route::post('/students/getstudent', [StudentController::class, 'getstudents'])->name('students.getstudent');
Route::get('/students/getstudent/{sid}/{did}', [StudentController::class, 'getstudents'])->name('students.getstudent');
Route::post('/students/getstudent', [StudentController::class, 'getstudents'])->name('students.getstudent.post');
Route::post('/students/getstudentformarks', [StudentController::class, 'getstudentformarks'])->name('students.getstudentformarks');
Route::get('/students/add', [StudentController::class, 'showImportForm'])->name('students.importForm');
Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');

Route::get('/students/marksaddstudentlist/{division_id}/{subject_id}', [StudentController::class, 'StudentlistBydivisionorsubject'])->name('students.marksaddstudentlist')->name('students.import');
Route::get('/marks', [MarkController::class, 'index'])->name('marks.index');
Route::get('/marks/create', [MarkController::class, 'create'])->name('marks.create');
Route::post('/marks/store', [MarkController::class, 'store'])->name('marks.store');
Route::post('/assign-subject', [StudentController::class, 'assignSubject'])->name('assign.subject');

Route::get('/students/edit/{id}', [StudentController::class, 'editstudent'])->name('students.edit');
Route::put('/students/update', [StudentController::class, 'updatestudent'])->name('students.edit');


});
Route::get('/standard', [ProfileController::class, 'edit'])->name('profile.edit');

require __DIR__.'/auth.php';
