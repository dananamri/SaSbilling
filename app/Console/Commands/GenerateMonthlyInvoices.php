<?php

namespace App\Console\Commands;

use App\Services\BillingService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = 'billing:generate {--date= : Date to generate invoices for (Y-m-d)}';

    protected $description = 'Generate monthly invoices for customers based on billing day';

    public function handle(BillingService $billingService): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : now();

        $count = $billingService->generateMonthlyInvoices($date);

        $this->info("Generated {$count} invoices for period {$date->format('Y-m')}.");

        return self::SUCCESS;
    }
}
