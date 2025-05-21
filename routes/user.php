<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\FilesController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

// Dashboard
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('api.dashboard');

// File Actions
Route::post('/share/file', [FilesController::class, 'shareFile']);
Route::post('/archive/file', [FilesController::class, 'archiveFile']);
Route::post('/file/reject/{id?}', [FilesController::class, 'rejectFile']); //
Route::post('/file/approve/{id?}', [FilesController::class, 'approveFile']); //
Route::get('/file/accept/{id?}', [FilesController::class, 'acceptFile']);
Route::get('/file/delete/{id?}', [FilesController::class, 'deleteFile']);


Route::get('/file/reverse/action/{id?}', [FilesController::class, 'reverseAction']);

// File Retrieval
Route::get('/new/files', [FilesController::class, 'newFiles']);
Route::get('/shared/files', [FilesController::class, 'sentFiles']);
Route::get('/received/files', [FilesController::class, 'receivedFiles']);
Route::get('/archived/files', [FilesController::class, 'archivedFiles']);
Route::get('/pending/files', [FilesController::class, 'pendingFiles']);
