<?php

use App\Livewire\Citas\Index as CitasIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('/dashboard', '/citas')->name('dashboard');
    Route::get('/citas', CitasIndex::class)->name('citas.index');
});

require __DIR__.'/settings.php';
