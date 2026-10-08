<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $dueDate = fake()->dateTimeBetween('+1 month', '+2 months');

        return [
            'invoice_number' => 'INV-'.fake()->unique()->numerify('########'),
            'customer_id' => Customer::factory(),
            'period' => $dueDate->format('Y-m'),
            'amount' => fake()->randomElement([150000, 250000, 500000]),
            'late_fee' => 0,
            'discount' => 0,
            'status' => InvoiceStatus::Unpaid,
            'due_date' => $dueDate,
            'paid_at' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvoiceStatus::Paid,
            'paid_at' => now(),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvoiceStatus::Overdue,
            'due_date' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }

    public function partial(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvoiceStatus::Partial,
        ]);
    }
}
