<?php

namespace App\Providers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\WhatsAppNotification;
use App\Policies\InvoicePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\TicketPolicy;
use App\Policies\WhatsAppNotificationPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        Ticket::class => TicketPolicy::class,
        WhatsAppNotification::class => WhatsAppNotificationPolicy::class,
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
        URL::forceScheme('https');

        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
