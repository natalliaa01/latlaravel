<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\InstrukturIndex;
use App\Livewire\KursusIndex;
use App\Livewire\PendaftaranIndex;
use App\Livewire\MateriIndex;
use App\Livewire\KursusPesertaCount;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Livewire Routes for CRUD
    Route::get('/instruktur', InstrukturIndex::class)->name('instruktur.index');
    Route::get('/kursus', KursusIndex::class)->name('kursus.index');
    Route::get('/pendaftaran', PendaftaranIndex::class)->name('pendaftaran.index');
    Route::get('/materi', MateriIndex::class)->name('materi.index');

    // Livewire Route for Report
    Route::get('/kursus-peserta-count', KursusPesertaCount::class)->name('kursus.peserta.count');
});

require __DIR__.'/auth.php';
