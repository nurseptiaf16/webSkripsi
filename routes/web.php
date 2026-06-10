<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OltController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ImportController;

// Halaman awal redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route khusus admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('olts', OltController::class);
    Route::resource('users', UserController::class);
    Route::get('import', [ImportController::class, 'index'])
         ->name('import.index');
    Route::post('import/upload', [ImportController::class, 'import'])
         ->name('import.upload');
    

     Route::get('notifications', [NotificationController::class, 'index'])
          ->name('notifications.index');
     Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])
          ->name('notifications.read');
     Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])
          ->name('notifications.markAllRead');
     Route::post('notifications/clear', [NotificationController::class, 'clearAll'])
          ->name('notifications.clear');
     Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])
          ->name('notifications.unreadCount');
});

// Route yang bisa diakses admin DAN manajer
Route::middleware(['auth', 'role:admin,manajer'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])
         ->name('dashboard');
    Route::get('monitoring', [MonitoringController::class, 'index'])
         ->name('monitoring');
    Route::get('prediction', [PredictionController::class, 'index'])
         ->name('prediction');
    Route::get('reports', [ReportController::class, 'index'])
         ->name('reports');
    Route::post('reports/export-pdf', [ReportController::class, 'exportPdf'])
         ->name('reports.export');
    Route::get('evaluation', [EvaluationController::class, 'index'])
         ->name('evaluation');
    
    // Tambahkan ini
    Route::get('profile', [ProfileController::class, 'index'])
         ->name('profile.index');
    Route::get('profile/edit', [ProfileController::class, 'edit'])
         ->name('profile.edit');
    Route::get('profile/password', [ProfileController::class, 'password'])
         ->name('profile.password.edit');
    Route::patch('profile', [ProfileController::class, 'update'])
         ->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])
         ->name('profile.destroy');
    Route::patch('profile/password', [ProfileController::class, 'updatePassword'])
         ->name('profile.password');
});

require __DIR__.'/auth.php';