<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HotspotMemberController;
use App\Http\Controllers\Admin\HotspotProfileController;
use App\Http\Controllers\Admin\HotspotVoucherController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\IsolirController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\LoginController as CustomerLoginController;
use App\Http\Controllers\Customer\ConnectionController as CustomerConnectionController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\InvoiceController as CustomerInvoiceController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\PackageController as CustomerPackageController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\SettingsController as CustomerSettingsController;
use App\Http\Controllers\Customer\TicketController as CustomerTicketController;
use App\Http\Controllers\Customer\UsageController as CustomerUsageController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Customer Portal
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [CustomerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerLoginController::class, 'login'])->name('login.post');
    Route::get('/register', [CustomerLoginController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CustomerLoginController::class, 'register'])->name('register.post');
    Route::post('/logout', [CustomerLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth:customer'])->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/invoices', [CustomerInvoiceController::class, 'index'])->name('invoices');
        Route::get('/invoices/{invoice}', [CustomerInvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/download', [CustomerInvoiceController::class, 'download'])->name('invoices.download');
        Route::get('/invoices/{invoice}/print', [CustomerInvoiceController::class, 'print'])->name('invoices.print');
        Route::get('/payments', [CustomerPaymentController::class, 'index'])->name('payments');
        Route::get('/payments/{payment}', [CustomerPaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/receipt', [CustomerPaymentController::class, 'downloadReceipt'])->name('payments.receipt');

        // Paket Saya
        Route::get('/packages', [CustomerPackageController::class, 'index'])->name('packages');

        // Status Koneksi
        Route::get('/connection', [CustomerConnectionController::class, 'index'])->name('connection');

        // Pemakaian
        Route::get('/usage', [CustomerUsageController::class, 'index'])->name('usage');

        // Pengaduan / Ticket
        Route::get('/tickets', [CustomerTicketController::class, 'index'])->name('tickets');
        Route::get('/tickets/create', [CustomerTicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [CustomerTicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticket}', [CustomerTicketController::class, 'show'])->name('tickets.show');

        // Notifikasi
        Route::get('/notifications', [CustomerNotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{notification}/read', [CustomerNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [CustomerNotificationController::class, 'markAllAsRead'])->name('notifications.read-all');

        // Profil
        Route::get('/profile', [CustomerProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');

        // Pengaturan
        Route::get('/settings', [CustomerSettingsController::class, 'index'])->name('settings');
        Route::put('/settings/notifications', [CustomerSettingsController::class, 'updateNotificationSettings'])->name('settings.notifications');
    });
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('packages', PackageController::class);
    Route::resource('invoices', InvoiceController::class)->except(['edit', 'update']);
    Route::resource('payments', PaymentController::class)->except(['edit', 'update', 'destroy']);

    Route::get('/isolir', [IsolirController::class, 'index'])->name('isolir.index');
    Route::post('/isolir/{customer}/isolate', [IsolirController::class, 'isolate'])->name('isolir.isolate');
    Route::post('/isolir/{customer}/reopen', [IsolirController::class, 'reopen'])->name('isolir.reopen');
    Route::post('/isolir/check', [IsolirController::class, 'checkNow'])->name('isolir.check');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/send-pending', [NotificationController::class, 'sendPending'])->name('notifications.send-pending');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Hotspot Management
    Route::prefix('hotspot')->name('hotspot.')->group(function () {
        Route::get('rekap', [HotspotVoucherController::class, 'rekap'])->name('rekap.index');
        Route::resource('profiles', HotspotProfileController::class)->except(['show']);
        Route::get('vouchers', [HotspotVoucherController::class, 'index'])->name('vouchers.index');
        Route::get('vouchers/print-all', [HotspotVoucherController::class, 'printAll'])->name('vouchers.print-all');
        Route::get('vouchers/create', [HotspotVoucherController::class, 'create'])->name('vouchers.create');
        Route::post('vouchers', [HotspotVoucherController::class, 'store'])->name('vouchers.store');
        Route::delete('vouchers/{voucher}', [HotspotVoucherController::class, 'destroy'])->name('vouchers.destroy');
        Route::get('vouchers/{voucher}/print', [HotspotVoucherController::class, 'print'])->name('vouchers.print');
        Route::resource('members', HotspotMemberController::class)->except(['show']);
    });
});
