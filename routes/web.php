<?php

use App\Http\Controllers\DetailController;
use App\Http\Controllers\PhotoActivityController;
use App\Models\TabelUmum;
use App\Models\TableUmumPhotoActivity;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('select');
});

Route::get('/monitoring-do-&-spk/{id}', [DetailController::class, 'show'])->name('monitoring.do.spk');
Route::get('/search-table/{cabangId}/{tempatId}', [DetailController::class, 'searchTableUmum']);
Route::post('/submit-endpoint/{tabelUmumId}', [DetailController::class, 'submit']);
Route::get('/get-data/{tabelUmumId}', [DetailController::class, 'getData']);

Route::get('/photo-activity/index/monitoring', [PhotoActivityController::class, 'index'])->name('photo-activity.monitoring');
Route::resource('photo-activity', PhotoActivityController::class)->except(['create', 'edit', 'show']);
Route::post('/photo-activity', [PhotoActivityController::class, 'store'])->name('photo-activity.store');
Route::get('/photo-activity/data', [PhotoActivityController::class, 'getData'])->name('photo-activity.data');
Route::delete('/photo-activity/{photoActivity}', [PhotoActivityController::class, 'destroy'])->name('photo-activity.destroy');
Route::post('/photo-activity/{id_photo_activity}/rename', [PhotoActivityController::class, 'rename'])->name('photo-activity.rename');
Route::get('/photo-activity/{id_photo_activity}', [PhotoActivityController::class, 'show'])->name('photo-event.show');
Route::put('/photo-activity/{id_photo_activity}', [PhotoActivityController::class, 'update'])->name('photo-activity.update');

// Endpoint baru: search table_umum_photo_activities by cabang & kategori
Route::get('/search-table-umum-photo-activity/{cabangId}/{kategori}', function ($cabangId, $kategori) {
    $row = TableUmumPhotoActivity::where('cabang_id', $cabangId)
        ->where('kategori', $kategori)
        ->first();
    return response()->json([
        'id' => $row ? $row->id : null
    ]);
});

Route::get('/api/photo-activity-list', function (Request $request) {
    return \App\Models\TableUmumPhotoActivity::with('cabang')
        ->where('cabang_id', $request->cabang_id)
        ->get()
        ->map(function($item) {
            return [
                'id' => $item->id,
                'kategori' => $item->kategori,
                'kategori_text' => ucwords(str_replace(['_', '.'], [' ', ' '], $item->kategori)),
                'cabang_id' => $item->cabang_id,
                'cabang_nama' => optional($item->cabang)->nama,
            ];
        });
});




