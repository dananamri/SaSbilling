<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'amount' => fake()->randomElement([150000, 250000, 500000]),
            'method' => fake()->randomElement(PaymentMethod::cases()),
            'status' => 'success',
            'reference' => fake()->optional()->numerify('TRX########'),
            'paid_by' => fake()->name(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
