<?php

use App\Http\Controllers\API\Admin\AppController as AdminAppController;
use App\Http\Controllers\API\Admin\AuditLogController;
use App\Http\Controllers\API\Admin\StatsController as AdminStatsController;
use App\Http\Controllers\API\Admin\UserController as AdminUserController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\MeController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Publisher\AppController;
use App\Http\Controllers\API\Publisher\StatsController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', RegisterController::class);
    Route::post('/login', LoginController::class);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', LogoutController::class);
        Route::get('/me', MeController::class);
    });
});

Route::middleware(['auth:sanctum', 'user.not.banned'])->group(function () {
    
    Route::prefix('apps')->group(function () {
        Route::get('/', [AppController::class, 'index']);
        Route::post('/', [AppController::class, 'store']);
        Route::get('/{app}', [AppController::class, 'show']);
        Route::put('/{app}', [AppController::class, 'update']);
        Route::delete('/{app}', [AppController::class, 'destroy']);
        Route::get('/{app}/stats', [StatsController::class, 'show']);
    });

    Route::prefix('admin')->middleware('user.admin')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::post('/users/{user}/ban', [AdminUserController::class, 'ban']);
        Route::post('/users/{user}/unban', [AdminUserController::class, 'unban']);
        
        Route::get('/apps', [AdminAppController::class, 'index']);
        
        Route::get('/stats', [AdminStatsController::class, 'index']);
        
        Route::get('/audit-logs', [AuditLogController::class, 'index']);
    });
});
