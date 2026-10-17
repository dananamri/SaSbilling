<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BandwidthController;
use App\Http\Controllers\Admin\BookEntryController;
use App\Http\Controllers\Admin\CashDepositController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GacsController;
use App\Http\Controllers\Admin\HelpdeskTicketController;
use App\Http\Controllers\Admin\HotspotMemberController;
use App\Http\Controllers\Admin\HotspotProfileController;
use App\Http\Controllers\Admin\HotspotVoucherController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\IsolirController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\OltController;
use App\Http\Controllers\Admin\PackageChangeRequestController as AdminPackageChangeRequestController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentTicketController;
use App\Http\Controllers\Admin\PppoeController;
use App\Http\Controllers\Admin\RadiusController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RouterController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\LoginController as CustomerLoginController;
use App\Http\Controllers\Customer\ConnectionController as CustomerConnectionController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\HistoryController as CustomerHistoryController;
use App\Http\Controllers\Customer\InvoiceController as CustomerInvoiceController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\PackageController as CustomerPackageController;
use App\Http\Controllers\Customer\PaymentController as CustomerPaymentController;
use App\Http\Controllers\Customer\PaymentTicketController as CustomerPaymentTicketController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\SettingsController as CustomerSettingsController;
use App\Http\Controllers\Customer\TicketController as CustomerTicketController;
use App\Http\Controllers\Customer\UsageController as CustomerUsageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth('customer')->check()) {
        return redirect()->route('customer.dashboard');
    }
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Forgot & Reset Password Mandiri (Tanpa WhatsApp)
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::get('/customer/forgot-password', fn () => redirect()->route('password.request', ['type' => 'customer']))->name('customer.password.request');
Route::get('/admin/forgot-password', fn () => redirect()->route('password.request', ['type' => 'admin']))->name('admin.password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetOtp'])->name('password.email');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Customer Portal
Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [CustomerLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [CustomerLoginController::class, 'login'])->name('login.post');
    Route::get('/register', [CustomerLoginController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [CustomerLoginController::class, 'register'])->name('register.post');
    Route::get('/pending-approval', [CustomerLoginController::class, 'pendingApproval'])->name('pending-approval');
    Route::post('/logout', [CustomerLoginController::class, 'logout'])->name('logout');

    Route::middleware(['auth:customer', 'customer.approved'])->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/history', [CustomerHistoryController::class, 'index'])->name('history');
        Route::post('/payment-tickets', [CustomerPaymentTicketController::class, 'store'])->name('payment-tickets.store');
        Route::get('/invoices', [CustomerInvoiceController::class, 'index'])->name('invoices');
        Route::get('/invoices/{invoice}', [CustomerInvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/download', [CustomerInvoiceController::class, 'download'])->name('invoices.download');
        Route::get('/invoices/{invoice}/print', [CustomerInvoiceController::class, 'print'])->name('invoices.print');
        Route::get('/payments', [CustomerPaymentController::class, 'index'])->name('payments');
        Route::get('/payments/{payment}', [CustomerPaymentController::class, 'show'])->name('payments.show');
        Route::get('/payments/{payment}/receipt', [CustomerPaymentController::class, 'downloadReceipt'])->name('payments.receipt');

        // Paket Saya
        Route::get('/packages', [CustomerPackageController::class, 'index'])->name('packages');
        Route::post('/packages/upgrade-request', [CustomerPackageController::class, 'requestUpgrade'])->name('packages.upgrade-request');

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
        Route::put('/profile/photo', [CustomerProfileController::class, 'updatePhoto'])->name('profile.photo');
        Route::put('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');

        // Pengaturan
        Route::get('/settings', [CustomerSettingsController::class, 'index'])->name('settings');
        Route::put('/settings/notifications', [CustomerSettingsController::class, 'updateNotificationSettings'])->name('settings.notifications');
    });
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::post('/customers/{customer}/approve', [CustomerController::class, 'approve'])->name('customers.approve');
    Route::post('/customers/{customer}/reject', [CustomerController::class, 'reject'])->name('customers.reject');
    Route::resource('packages', PackageController::class);
    Route::get('/package-change-requests', [AdminPackageChangeRequestController::class, 'index'])->name('package-change-requests.index');
    Route::post('/package-change-requests/{packageChangeRequest}/approve', [AdminPackageChangeRequestController::class, 'approve'])->name('package-change-requests.approve');
    Route::post('/package-change-requests/{packageChangeRequest}/reject', [AdminPackageChangeRequestController::class, 'reject'])->name('package-change-requests.reject');
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

    // Manajemen / Users & Activity
    Route::resource('users', UserController::class)->except(['show']);
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Keuangan ISP
    Route::resource('book-entries', BookEntryController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('cash-deposits', CashDepositController::class)->only(['index', 'create', 'store']);
    Route::post('/cash-deposits/{cashDeposit}/approve', [CashDepositController::class, 'approve'])->name('cash-deposits.approve');
    Route::post('/cash-deposits/{cashDeposit}/reject', [CashDepositController::class, 'reject'])->name('cash-deposits.reject');
    Route::resource('payment-tickets', PaymentTicketController::class)->parameters(['payment-tickets' => 'paymentTicket'])->only(['index', 'show']);
    Route::post('/payment-tickets/{paymentTicket}/approve', [PaymentTicketController::class, 'approve'])->name('payment-tickets.approve');
    Route::post('/payment-tickets/{paymentTicket}/reject', [PaymentTicketController::class, 'reject'])->name('payment-tickets.reject');

    // Network - MikroTik
    Route::resource('routers', RouterController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('/routers/{router}/ping', [RouterController::class, 'ping'])->name('routers.ping');
    Route::get('/pppoe', [PppoeController::class, 'index'])->name('pppoe.index');
    Route::get('/pppoe/create', [PppoeController::class, 'create'])->name('pppoe.create');
    Route::post('/pppoe', [PppoeController::class, 'store'])->name('pppoe.store');
    Route::delete('/pppoe/{pppoeSecret}', [PppoeController::class, 'destroy'])->name('pppoe.destroy');
    Route::get('/pppoe-profiles', [PppoeController::class, 'profiles'])->name('pppoe.profiles');
    Route::post('/pppoe-profiles', [PppoeController::class, 'storeProfile'])->name('pppoe.profiles.store');
    Route::get('/bandwidth', [BandwidthController::class, 'index'])->name('bandwidth.index');
    Route::get('/bandwidth/{router}/chart', [BandwidthController::class, 'chart'])->name('bandwidth.chart');
    Route::post('/bandwidth/{router}/poll', [BandwidthController::class, 'poll'])->name('bandwidth.poll');

    // Advanced Network
    Route::resource('olts', OltController::class)->only(['index', 'create', 'store']);
    Route::post('/olts/{olt}/poll', [OltController::class, 'poll'])->name('olts.poll');
    Route::get('/gacs', [GacsController::class, 'index'])->name('gacs.index');
    Route::get('/radius', [RadiusController::class, 'index'])->name('radius.index');
    Route::post('/radius', [RadiusController::class, 'store'])->name('radius.store');

    // GIS & Tenant
    Route::get('/map', [MapController::class, 'index'])->name('map.index');
    Route::get('/map/geojson', [MapController::class, 'geojson'])->name('map.geojson');
    Route::resource('tenants', TenantController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    // Operasional SDM
    Route::get('/tickets', [HelpdeskTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [HelpdeskTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/assign', [HelpdeskTicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/resolve', [HelpdeskTicketController::class, 'resolve'])->name('tickets.resolve');
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::post('/attendances', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::post('/attendances/clock-in', [AttendanceController::class, 'clockIn'])->name('attendances.clock-in');
    Route::post('/attendances/clock-out', [AttendanceController::class, 'clockOut'])->name('attendances.clock-out');

    // Backup Sistem
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/run', [BackupController::class, 'run'])->name('backup.run');
    Route::get('/backup/download/{filename}', [BackupController::class, 'download'])->name('backup.download');
});
