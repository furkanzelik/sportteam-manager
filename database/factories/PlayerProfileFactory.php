<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlayerProfile>
 */
class PlayerProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // user_id zetten we later
            'number' => $this->faker->numberBetween(1, 99),
            'position' => $this->faker->randomElement(['Keeper','Verdediger','Middenvelder','Aanvaller']),
            'bio' => $this->faker->sentence(10),
            'avatar_path' => null,
        ];
    }
}
