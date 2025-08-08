<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Vues;
use Illuminate\Support\Facades\Route;
use App\Livewire\Utilisateurs;
use App\Http\Controllers\ActivateUser;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['is_autenticated', 'is_suspended'])->group(function () {
    Route::get('/consommations', [Vues::class, 'consommation'])->name('consommations.index');
    Route::middleware('adminOrDistrict')->group(function () {
        Route::get('/fs', [Vues::class, 'fs'])->name('fs.index');
        Route::get('synthese-district', [Vues::class, 'synthese_district'])->name('synthese.district');
    });
    Route::middleware('is_admin')->group(function () {
        Route::get('/utilisateurs', [Vues::class, 'utilisateurs'])->name('utilisateurs.index');
        Route::get('/regions', [Vues::class, 'regions'])->name('regions.index');
        Route::get('/districts', [Vues::class, 'districts'])->name('districts.index');
        Route::get('/medicaments', [Vues::class, 'medicament'])->name('medicaments.index');
    });
    Route::get('/dashboard', [Vues::class, 'dashboard'])->name('dashboard');
});
Route::get('/activation-compte/{token}', [ActivateUser::class, 'showActivate'])
    ->name('activation.compte');
Route::post('/definir-password/{id}', [ActivateUser::class, 'defineNewPassword'])->name('definir.password');



/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});*/

require __DIR__ . '/auth.php';
