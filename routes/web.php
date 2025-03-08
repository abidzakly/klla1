<?php

use App\Http\Controllers\DetailController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('select');
});


// Route::get('/publicDisplay/{id}', [DetailController::class, 'show'])->name('public.display');
// Route::get('/customerGathering/{id}', [DetailController::class, 'show'])->name('customer.gathering');
// Route::get('/digitalMarketing/{id}', [DetailController::class, 'show'])->name('digital.marketing');
// Route::get('/grassroot/{id}', [DetailController::class, 'show'])->name('grassroot');
Route::get('/monitoring-do-&-spk/{id}', [DetailController::class, 'show'])->name('monitoring.do.spk');
Route::get('/search-table/{cabangId}/{tempatId}', [DetailController::class, 'searchTableUmum']);
Route::post('/submit-endpoint/{tabelUmumId}', [DetailController::class, 'submit']);
Route::get('/get-data/{tabelUmumId}', [DetailController::class, 'getData']);




