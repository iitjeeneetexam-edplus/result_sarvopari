<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StandardController;



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/standard', [ProfileController::class, 'edit'])->name('profile.edit');

require __DIR__.'/auth.php';
Route::get('/schools', [SchoolController::class, 'index'])->name('schools.index');
Route::get('/school/create', [SchoolController::class, 'create'])->name('school.create');
Route::post('/school/store', [SchoolController::class, 'store'])->name('school.store');



Route::get('/standards', [StandardController::class, 'index'])->name('standards.index');


