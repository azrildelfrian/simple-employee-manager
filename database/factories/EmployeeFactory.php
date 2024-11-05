<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'position' => $this->faker->jobTitle,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'start_date' => $this->faker->date(),
            'salary' => $this->faker->numberBetween(3000000, 15000000) // Gaji antara 3 juta dan 15 juta
        ];
    }
}
