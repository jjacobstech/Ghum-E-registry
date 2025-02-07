<?php

use App\Models\Files;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\FilesController;

Route::get('/web', function () {
    return view('welcome');
});

Route::get('/dashboard', [HomeController::class, 'dashboard']);

// File Actions
Route::post('/share/file', [FilesController::class, 'shareFile']);
Route::post('/archive/file', [FilesController::class, 'archiveFile']);
Route::post('/file/reject/{id}', [FilesController::class, 'rejectFile']);
Route::post('/file/accept/{id}', [FilesController::class, 'acceptFile']);
Route::post('/file/delete/{id}', [FilesController::class, 'deleteFile']);

// File Retrieval
Route::get('/shared/file', [FilesController::class, 'sentFiles']);
Route::get('/received/file', [FilesController::class, 'receivedFiles']);
Route::get('/archived/file', [FilesController::class, 'archivedFiles']);