<?php

namespace App\Services;

use App\Enums\CustomerStatus;
use App\Enums\IsolirAction;
use App\Models\Customer;
use App\Models\IsolirLog;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IsolirService
{
    public function checkAndIsolate(): int
    {
        $thresholdDays = (int) Setting::get('isolir_threshold_days', 7);

        $customers = Customer::where('status', CustomerStatus::Active)
            ->whereHas('unpaidInvoices', fn ($q) => $q->where('due_date', '<', now()->subDays($thresholdDays)))
            ->get();

        $count = 0;

        foreach ($customers as $customer) {
            $this->isolate($customer, "Tunggakan melebihi {$thresholdDays} hari", true);
            $count++;
        }

        return $count;
    }

    public function checkAndReopen(): int
    {
        $customers = Customer::where('status', CustomerStatus::Isolated)
            ->get();

        $count = 0;

        foreach ($customers as $customer) {
            if ($customer->totalOutstanding() <= 0) {
                $this->reopen($customer, 'Tagihan lunas', true);
                $count++;
            }
        }

        return $count;
    }

    public function isolate(Customer $customer, string $reason, bool $isAutomatic = false): void
    {
        if ($customer->status === CustomerStatus::Isolated) {
            return;
        }

        DB::transaction(function () use ($customer, $reason, $isAutomatic) {
            $customer->status = CustomerStatus::Isolated;
            $customer->save();

            IsolirLog::create([
                'customer_id' => $customer->id,
                'action' => IsolirAction::Isolate,
                'reason' => $reason,
                'is_automatic' => $isAutomatic,
            ]);
        });

        Log::info("Customer {$customer->id} isolated: {$reason}");
    }

    public function reopen(Customer $customer, string $reason, bool $isAutomatic = false): void
    {
        if ($customer->status !== CustomerStatus::Isolated) {
            return;
        }

        DB::transaction(function () use ($customer, $reason, $isAutomatic) {
            $customer->status = CustomerStatus::Active;
            $customer->save();

            IsolirLog::create([
                'customer_id' => $customer->id,
                'action' => IsolirAction::Reopen,
                'reason' => $reason,
                'is_automatic' => $isAutomatic,
            ]);
        });

        Log::info("Customer {$customer->id} reopened: {$reason}");
    }
}
