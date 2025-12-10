<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileUploadController;

// MinIO File Upload Routes
Route::middleware(['auth:sanctum'])->prefix('files')->group(function () {
    // Get pre-signed upload URL (production recommended)
    Route::post('/upload-url', [FileUploadController::class, 'getUploadUrl']);

    // Direct upload via backend (local/fallback)
    Route::post('/upload', [FileUploadController::class, 'upload']);

    // Get temporary download URL
    Route::post('/download-url', [FileUploadController::class, 'getDownloadUrl']);

    // Delete file
    Route::delete('/delete', [FileUploadController::class, 'delete']);
});
