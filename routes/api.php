<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FolderController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\RecentlyDeletedController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\VaultController;
use Illuminate\Support\Facades\Route;

Route::middleware(['throttle:login'])->group(function () {
    Route::post('/login/check-email', [AuthController::class, 'checkEmail']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-2fa', [AuthController::class, 'verify2fa']);
    Route::post('/session/restore', [AuthController::class, 'restoreSession']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/onboarding/complete', [AuthController::class, 'completeOnboarding']);

    Route::get('/folders', [FolderController::class, 'index']);
    Route::post('/folders', [FolderController::class, 'store']);
    Route::post('/folders/reorder', [FolderController::class, 'reorder']);
    Route::put('/folders/{folder}', [FolderController::class, 'update']);
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy']);

    Route::get('/recently-deleted', [RecentlyDeletedController::class, 'index']);
    Route::post('/recently-deleted/{item}/restore', [RecentlyDeletedController::class, 'restore']);
    Route::delete('/recently-deleted/{item}', [RecentlyDeletedController::class, 'destroy']);

    Route::get('/tags', [TagController::class, 'index']);
    Route::post('/tags', [TagController::class, 'store']);
    Route::put('/tags/{tag}', [TagController::class, 'update']);
    Route::delete('/tags/{tag}', [TagController::class, 'destroy']);

    Route::get('/vault', [VaultController::class, 'status']);
    Route::post('/vault/pin', [VaultController::class, 'setPin']);
    Route::post('/vault/pin/change', [VaultController::class, 'changePin']);
    Route::post('/vault/notes', [VaultController::class, 'listHidden']);
    Route::post('/vault/hide-note/{note}', [VaultController::class, 'hideNote']);

    Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::post('/notes/reorder', [NoteController::class, 'reorder']);
    Route::get('/notes/{note}', [NoteController::class, 'show']);
    Route::put('/notes/{note}', [NoteController::class, 'update']);
    Route::delete('/notes/{note}', [NoteController::class, 'destroy']);

    Route::post('/notes/{note}/autosave', [NoteController::class, 'autosave']);
});
