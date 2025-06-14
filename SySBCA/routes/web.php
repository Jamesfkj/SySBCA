<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Vues;
use Illuminate\Support\Facades\Route;
use App\Livewire\Regions;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/regions', [Vues::class, 'regions'])->name('regions.index');
Route::get('/districts', [Vues::class, 'districts'])->name('districts.index');
Route::get('/fs', [Vues::class, 'fs'])->name('fs.index');
Route::get('/medicaments', [Vues::class, 'medicament'])->name('medicaments.index');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
