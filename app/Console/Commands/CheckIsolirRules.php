<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\IsolirService;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckIsolirRules extends Command
{
    protected $signature = 'billing:isolir-check';

    protected $description = 'Check isolir rules and execute automatic isolate/reopen';

    public function handle(IsolirService $isolirService, NotificationService $notificationService): int
    {
        $isolatedCount = $isolirService->checkAndIsolate();

        foreach (Customer::where('status', 'isolated')->get() as $customer) {
            $notificationService->sendIsolatedNotification($customer);
        }

        $reopenedCount = $isolirService->checkAndReopen();

        foreach (Customer::where('status', 'active')->get() as $customer) {
            if ($customer->isolirLogs()->where('action', 'reopen')->where('created_at', '>=', now()->subDay())->exists()) {
                $notificationService->sendReopenedNotification($customer);
            }
        }

        $this->info("Isolated: {$isolatedCount}, Reopened: {$reopenedCount}");

        return self::SUCCESS;
    }
}
