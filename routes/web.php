<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedecineController;


Route::get('/', [MedecineController::class, 'index'])->name('index');
Route::put('/medecine/{id}', [MedecineController::class, 'updateMedecine'])->name('updateMedecine');

