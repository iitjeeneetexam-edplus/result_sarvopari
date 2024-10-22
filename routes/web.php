<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
Route::get('/school/create', [SchoolController::class, 'create'])->name('school.create');
Route::post('/school/store', [SchoolController::class, 'store'])->name('school.store');

//standard
use App\Http\Controllers\StandardController;

Route::get('/standards', [StandardController::class, 'index'])->name('standards.index');


