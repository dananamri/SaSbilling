<?php

namespace Database\Factories;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => '08'.fake()->numerify('##########'),
            'email' => fake()->safeEmail(),
            'address' => fake()->address(),
            'package_id' => Package::factory(),
            'status' => CustomerStatus::Active,
            'billing_day' => fake()->numberBetween(1, 28),
            'joined_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function isolated(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CustomerStatus::Isolated,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CustomerStatus::Inactive,
        ]);
    }
}
