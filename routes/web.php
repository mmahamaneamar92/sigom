<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MaterielController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.log');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('materiels', 'MaterielController');

Route::resource('profiles', 'ProfileController');

Route::resource('gestions', 'UserMaterielController');

Route::resource('reparations', 'ReparationController');

Route::resource('bordereaux', 'BordereauController');

Route::resource('aps', 'ApController');

Route::resource('assures', 'AssureController');

Route::resource('hopitals', 'HopitalController');

Route::resource('pharmacies', 'PharmacieController');

Route::resource('prescripteurs', 'PrescripteurController');

Route::resource('prestations', 'PrestationController');

Route::resource('techniciens', 'TechnicienController');

Route::resource('types', 'TypeController');

Route::resource('utilisateurs', 'UtilisateurController');

Route::post('/get-prix', 'PrestationController@getPrix')->name('get-prix');

Route::post('/motDePasse', 'PrestationController@motDePasse')->name('motDePasse');

require __DIR__.'/auth.php';
