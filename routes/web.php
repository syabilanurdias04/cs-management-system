<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\KaryawanController;
Route::get('/karyawan/export', [KaryawanController::class, 'export'])->name('karyawan.export');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function() {
        return view('dashboard');
    })->name('dashboard');
    
    Route::resource('karyawan', KaryawanController::class);
    
});