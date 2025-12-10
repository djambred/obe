<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Illuminate\Support\Facades\Response;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/
Route::get('/', function () {
    return view('welcome');
});

// RPS PDF Preview (inline)
Route::get('/rps/{rps}/preview-pdf', [\App\Http\Controllers\RpsController::class, 'previewPdf'])
    ->name('rps.preview-pdf')
    ->middleware(['auth']);

// RPS PDF Preview (inline)
Route::get('/rps/{rps}/preview-pdf', [\App\Http\Controllers\RpsController::class, 'previewPdf'])
    ->name('rps.preview-pdf')
    ->middleware(['auth']);

// RPS PDF Download
Route::get('/rps/{rps}/download-pdf', [\App\Http\Controllers\RpsController::class, 'downloadPdf'])
    ->name('rps.download-pdf')
    ->middleware(['auth']);

// RPS Public Verification (No Auth Required)
Route::get('/verify/rps/{rps}', [\App\Http\Controllers\RpsController::class, 'verifyRps'])
    ->name('rps.verify');
