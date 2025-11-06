<?php
// routes/web.php
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Organizer\DashboardController as OrgDashboard;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::view('/', 'welcome')->name('home');

require __DIR__.'/auth.php';
require __DIR__.'/profile.php';
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');


// Area umum (harus login + email verified)
Route::middleware(['auth','verified'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard'); // default dashboard
});

// Admin
Route::prefix('admin')
    ->middleware(['auth','verified','role:admin'])
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
    });

// Organizer
Route::prefix('organizer')
    ->middleware(['auth','verified','role:organizer'])
    ->group(function () {
        Route::get('/dashboard', OrgDashboard::class)->name('organizer.dashboard');
    });
