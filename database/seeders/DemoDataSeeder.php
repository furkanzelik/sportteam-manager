<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Competition;
use App\Models\Game;
use App\Models\PlayerProfile;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Zorg voor een coach + spelers (kan ook met jouw UserSeeder samenwerken)
        $coach = User::firstOrCreate(
            ['email' => 'coach@example.com'],
            ['name' => 'Head Coach', 'role' => Role::Coach, 'password' => bcrypt('password')]
        );

        $players = User::factory()
            ->count(10)
            ->create(['role' => Role::Player->value]);

        // Profielen voor spelers
        foreach ($players as $p) {
            PlayerProfile::factory()->create(['user_id' => $p->id]);
        }

        // 2) Competities
        $competitions = Competition::factory()->count(2)->create();

        // 3) Teams (2 teams) met coach + competitie
        $teamA = Team::factory()->create([
            'name' => 'Amsterdam FC',
            'coach_id' => $coach->id,
            'competition_id' => $competitions->first()->id,
        ]);

        $teamB = Team::factory()->create([
            'name' => 'Rotterdam United',
            'coach_id' => $coach->id,
            'competition_id' => $competitions->last()->id,
        ]);

        // 4) Spelers verdelen over teams (5 en 5)
        $playersA = $players->take(5);
        $playersB = $players->slice(5, 5);

        $teamA->players()->sync($playersA->pluck('id')->all());
        $teamB->players()->sync($playersB->pluck('id')->all());

        // 5) Wedstrijden genereren
        // Zorg dat home != away
        Game::factory()->count(4)->create([
            'home_team_id' => $teamA->id,
            'away_team_id' => $teamB->id,
        ]);

        Game::factory()->count(4)->create([
            'home_team_id' => $teamB->id,
            'away_team_id' => $teamA->id,
        ]);
    }
}
