<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // home_team_id & away_team_id zetten we in seeder
            'starts_at' => $this->faker->dateTimeBetween('+1 days', '+2 months'),
            'location' => $this->faker->city(),
            'status' => 'scheduled',
        ];
    }
}
