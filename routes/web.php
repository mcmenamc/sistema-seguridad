<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\IncidentTypeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::middleware(['role:Administrador|Operador|Cliente'])->group(function () {
        Route::resource('devices', DeviceController::class);
        Route::resource('events', EventController::class);
        Route::resource('incidents', IncidentController::class);
    });

    Route::middleware(['role:Administrador|Operador'])->group(function () {
        Route::resource('device-types', DeviceTypeController::class);
        Route::resource('incident-types', IncidentTypeController::class);
    });
    
    Route::middleware(['role:Administrador'])->group(function () {
        Route::get('/logs', [AuditLogController::class, 'index'])->name('logs.index');
        Route::resource('users', UserController::class);
    });
    
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::delete('/notifications/clear', [NotificationController::class, 'clearAll'])->name('notifications.clear');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
