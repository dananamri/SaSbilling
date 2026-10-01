<?php

namespace Database\Factories;

use App\Models\Package;
use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = Package::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'speed' => fake()->randomElement(['10 Mbps', '20 Mbps', '50 Mbps', '100 Mbps']),
            'price' => fake()->randomElement([100000, 150000, 250000, 500000]),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
