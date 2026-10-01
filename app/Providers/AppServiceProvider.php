<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\WhatsAppNotification;
use App\Policies\InvoicePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\TicketPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        Ticket::class => TicketPolicy::class,
        WhatsAppNotification::class => TicketPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
