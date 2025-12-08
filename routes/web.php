<?php
// routes/web.php
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Organizer\DashboardController as OrgDashboard;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\DataMasterController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\UserTransactionController;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');


require __DIR__.'/auth.php';
require __DIR__.'/profile.php';
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

Route::middleware('auth')->group(function () {
    Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success'); // Moved up
    Route::get('/checkout/{ticket_type_id}', [App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{ticket_type_id}', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');
});

// Area umum (harus login + email verified)
Route::middleware(['auth','verified'])->group(function () {
    Route::view(uri: '/dashboard', view: 'dashboard')->name(name: 'dashboard');
    Route::view('/settings', 'user.settings')->name('user.settings'); // default dashboard

    Route::get('/transactions', [UserTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/tickets', [UserTransactionController::class, 'tickets'])->name('transactions.tickets');
    Route::get('/transactions/tickets/{ticket}', [UserTransactionController::class, 'showTicket'])->name('transactions.ticket.show');
    Route::get('/fix-tickets', [\App\Http\Controllers\FixTicketController::class, 'fix']);
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
        Route::get('/laporan', [LaporanController::class, 'index'])->name('admin.laporan');

    });


// Organizer Routes
Route::middleware(['auth', 'verified'])->prefix('organizer')->name('organizer.')->group(function () {

    // Pending verification page (outside organizer middleware)
    Route::get('/pending-verification', function () {
        return view('organizer.pending-verification');
    })->name('pending-verification');

    // Protected organizer routes
    Route::middleware(['organizer'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Organizer\DashboardController::class, 'index'])
            ->name('dashboard');

        // Events Management
        Route::resource('events', \App\Http\Controllers\Organizer\EventController::class);

        // Tickets Management
        Route::get('events/{event}/tickets', [\App\Http\Controllers\Organizer\TicketController::class, 'index'])
            ->name('events.tickets.index');
        Route::get('tickets/{ticket}/download', [\App\Http\Controllers\Organizer\TicketController::class, 'download'])
            ->name('tickets.download');

        // Profile
        Route::get('/profile', [\App\Http\Controllers\Organizer\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Organizer\ProfileController::class, 'update'])
            ->name('profile.update');

        // Statistics
        Route::get('/statistics', [\App\Http\Controllers\Organizer\DashboardController::class, 'statistics'])
            ->name('statistics');
            
        // Export Buyers
        Route::get('/events/{event}/export-buyers', [\App\Http\Controllers\Organizer\DashboardController::class, 'exportBuyers'])
            ->name('events.export-buyers');
            
        // Verify Ticket
        Route::post('/events/{event}/verify-ticket', [\App\Http\Controllers\Organizer\TicketController::class, 'verify'])
            ->name('events.verify-ticket');
    });
});
