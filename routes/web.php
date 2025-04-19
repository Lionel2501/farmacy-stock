<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedecineController;


Route::get('/', [MedecineController::class, 'index'])->name('index');
Route::post('/handle-stock', [MedecineController::class, 'handleStock'])->name('handleStock');
Route::put('/medecine/{id}', [MedecineController::class, 'updateMedecine'])->name('updateMedecine');
Route::post('/store-item', [MedecineController::class, 'storeItem'])->name('storeItem');
Route::delete('/delete-item/{id}', [MedecineController::class, 'deleteItem'])->name('deleteItem');

