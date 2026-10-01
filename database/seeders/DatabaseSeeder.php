<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('company_name', 'SaSbilling ISP');
        Setting::set('company_phone', '081234567890');
        Setting::set('company_address', 'Jl. Contoh No. 123');
        Setting::set('isolir_threshold_days', 7);
        Setting::set('reminder_days_before', 3);

        $packages = [
            ['name' => 'Home Basic', 'speed' => '10 Mbps', 'price' => 100000],
            ['name' => 'Home Pro', 'speed' => '20 Mbps', 'price' => 150000],
            ['name' => 'Home Premium', 'speed' => '50 Mbps', 'price' => 250000],
            ['name' => 'Business', 'speed' => '100 Mbps', 'price' => 500000],
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }

        $packageIds = Package::pluck('id');

        for ($i = 0; $i < 20; $i++) {
            $customer = Customer::create([
                'name' => fake()->name(),
                'phone' => '08'.fake()->numerify('##########'),
                'email' => fake()->safeEmail(),
                'address' => fake()->address(),
                'package_id' => $packageIds->random(),
                'status' => 'active',
                'billing_day' => fake()->numberBetween(1, 28),
                'joined_at' => fake()->dateTimeBetween('-1 year', 'now'),
            ]);

            for ($j = 0; $j < 3; $j++) {
                $invoice = Invoice::create([
                    'invoice_number' => 'INV-'.now()->format('Ymd').str_pad((string) ($i * 10 + $j + 1), 4, '0', STR_PAD_LEFT),
                    'customer_id' => $customer->id,
                    'period' => now()->subMonths($j)->format('Y-m'),
                    'amount' => $customer->package->price,
                    'status' => $j === 0 ? 'unpaid' : 'paid',
                    'due_date' => now()->subMonths($j)->addDays(7),
                    'paid_at' => $j === 0 ? null : now()->subMonths($j)->addDays(5),
                ]);

                if ($j > 0) {
                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'amount' => $invoice->amount,
                        'method' => fake()->randomElement(['cash', 'transfer_bca', 'qris', 'dana']),
                        'paid_by' => $customer->name,
                    ]);
                }
            }
        }
    }
}
