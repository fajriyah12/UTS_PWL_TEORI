<?php
// routes/web.php
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Organizer\DashboardController as OrgDashboard;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DataMasterController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', HomeController::class)->name('home');

require __DIR__.'/auth.php';
require __DIR__.'/profile.php';
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

Route::get('/checkout/{ticketType}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{ticketType}', [CheckoutController::class, 'store'])->name('checkout.store');

// Area umum (harus login + email verified)
Route::middleware(['auth','verified'])->group(function () {
    Route::view(uri: '/dashboard', view: 'dashboard')->name(name: 'dashboard');
});

// Admin
Route::prefix('admin')
    ->middleware(['auth','verified','role:admin'])
    ->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/data-master', [DataMasterController::class, 'index'])->name('admin.datamaster');
        Route::get('/tickets/create', [DataMasterController::class, 'createTicket'])->name('admin.create-ticket');
        Route::get('/tickets/{ticketType}/edit', [DataMasterController::class, 'editTicket'])->name('admin.edit-ticket');
        Route::get('/data-master/search-tickets', [DataMasterController::class, 'searchTickets'])->name('admin.search-tickets');
        Route::get('/data-master/search-users', [DataMasterController::class, 'searchUsers'])->name('admin.search-users');
        Route::post('/data-master/tickets', [DataMasterController::class, 'storeTicket'])->name('admin.store-ticket');
        Route::put('/data-master/tickets/{ticketType}', [DataMasterController::class, 'updateTicket'])->name('admin.update-ticket');
        Route::delete('/data-master/tickets/{ticketType}', [DataMasterController::class, 'destroyTicket'])->name('admin.destroy-ticket');
        Route::delete('/data-master/users/{user}', [DataMasterController::class, 'destroyUser'])->name('admin.destroy-user');
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settingAdmin');
        Route::put('/settings/update-photo', [SettingController::class, 'updatePhoto'])->name('admin.update-photo');
        Route::delete('/settings/delete-photo', [SettingController::class, 'deletePhoto'])->name('admin.delete-photo');
        Route::put('/settings/personal-info', [SettingController::class, 'updatePersonalInfo'])->name('admin.update-personal-info');
        Route::put('/settings/password', [SettingController::class, 'changePassword'])->name('admin.change-password');
        Route::delete('/settings/profile', [SettingController::class, 'deleteProfile'])->name('admin.delete-profile');
    });

// Organizer
Route::prefix('organizer')
    ->middleware(['auth','verified','role:organizer'])
    ->group(function () {
        Route::get('/dashboard', OrgDashboard::class)->name('organizer.dashboard');
    });
