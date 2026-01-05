<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Competition;
use App\Models\Game;
use App\Models\LoginEvent;
use App\Models\PlayerProfile;
use App\Models\Team;
use App\Models\User;
use App\Models\MatchRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // === USERS ===
            // Coach (vast account)
            $coach = User::updateOrCreate(
                ['email' => 'coach@example.com'],
                [
                    'name' => 'Head Coach',
                    'role' => Role::Coach,
                    'password' => Hash::make('password'),
                ]
            );

            // Twee vaste spelers (handig om mee te testen)
            $player1 = User::updateOrCreate(
                ['email' => 'player1@example.com'],
                [
                    'name' => 'Speler Eén',
                    'role' => Role::Player,
                    'password' => Hash::make('password'),
                ]
            );
            $player2 = User::updateOrCreate(
                ['email' => 'player2@example.com'],
                [
                    'name' => 'Speler Twee',
                    'role' => Role::Player,
                    'password' => Hash::make('password'),
                ]
            );

            // Nog 8 extra random spelers via factory
            $morePlayers = User::factory()
                ->count(8)
                ->create(['role' => Role::Player->value]);

            // Verzamel alle spelers
            $players = collect([$player1, $player2])->merge($morePlayers)->values();

            // Profielen voor alle spelers
            foreach ($players as $p) {
                PlayerProfile::factory()->create(['user_id' => $p->id]);
            }

            // === COMPETITIONS (deterministisch, geen dubbels) ===
            $compA = Competition::updateOrCreate(['name' => 'Eerste Divisie']);
            $compB = Competition::updateOrCreate(['name' => 'Tweede Divisie']);

            // === TEAMS (coach + competitie) ===
            $teamA = Team::updateOrCreate(
                ['name' => 'Amsterdam FC'],
                [
                    'coach_id' => $coach->id,
                    'competition_id' => $compA->id,
                ]
            );

            $teamB = Team::updateOrCreate(
                ['name' => 'Rotterdam United'],
                [
                    'coach_id' => $coach->id,
                    'competition_id' => $compB->id,
                ]
            );

            // === Spelers verdelen over teams (5 en 5) ===
            $playersA = $players->take(5);
            $playersB = $players->slice(5, 5);

            $teamA->players()->syncWithoutDetaching($playersA->pluck('id')->all());
            $teamB->players()->syncWithoutDetaching($playersB->pluck('id')->all());

            // Zorg dat de twee vaste spelers ook gekoppeld zijn:
            $teamA->players()->syncWithoutDetaching([$player1->id]); // player1 in Amsterdam FC
            $teamB->players()->syncWithoutDetaching([$player2->id]); // player2 in Rotterdam United

            // === MATCHES / GAMES ===
            // We maken 4 wedstrijden: 2 recent (completed) + 2 komend (scheduled)
            // Let op: jouw Game-model moet 'protected $table = "matches";' hebben.

            $games = [
                // recent
                [
                    'home_team_id' => $teamA->id,
                    'away_team_id' => $teamB->id,
                    'starts_at'    => now()->subDays(7)->setTime(19, 30),
                    'location'     => 'Sporthal Noord',
                    'status'       => 'completed',
                ],
                [
                    'home_team_id' => $teamB->id,
                    'away_team_id' => $teamA->id,
                    'starts_at'    => now()->subDays(2)->setTime(20, 00),
                    'location'     => 'Stadion Oost',
                    'status'       => 'completed',
                ],
                // komend
                [
                    'home_team_id' => $teamA->id,
                    'away_team_id' => $teamB->id,
                    'starts_at'    => now()->addDays(2)->setTime(18, 00),
                    'location'     => 'Arena West',
                    'status'       => 'scheduled',
                ],
                [
                    'home_team_id' => $teamB->id,
                    'away_team_id' => $teamA->id,
                    'starts_at'    => now()->addDays(10)->setTime(20, 15),
                    'location'     => 'Sporthal Zuid',
                    'status'       => 'scheduled',
                ],
            ];

            foreach ($games as $g) {
                Game::updateOrCreate(
                    [
                        'home_team_id' => $g['home_team_id'],
                        'away_team_id' => $g['away_team_id'],
                        'starts_at'    => $g['starts_at'],
                    ],
                    $g
                );
            }

            // Login events voor player1 (minimaal 5 verschillende dagen voor schoolcriteria)
            if (class_exists(LoginEvent::class)) {
                for ($i = 1; $i <= 7; $i++) {
                    LoginEvent::firstOrCreate([
                        'user_id'    => $player1->id,
                        'created_at' => now()->subDays($i)->setTime(rand(9, 20), rand(0, 59)),
                        'updated_at' => now()->subDays($i)->setTime(rand(9, 20), rand(0, 59)),
                    ]);
                }
            }

            // === OPENSTAANDE VERZOEKEN (9 kaarten voor het forum / dashboard) ===
            // Posities & locaties voor variatie
            $positions = ['Keeper','Verdediger','Middenvelder','Aanvaller','Vleugel','Back'];
            $venues    = ['Sporthal Noord','Sporthal Zuid','Arena West','Stadion Oost','Veld 3','Veld 7','Complex Midden'];

            for ($i = 1; $i <= 9; $i++) {
                // Maak voor elke kaart een (toekomstige) wedstrijd of hergebruik dezelfde combinatie+tijd
                $startsAt = now()->addDays($i)->setTime(rand(18, 21), [0,15,30,45][array_rand([0,15,30,45])]);

                // Wissel thuis/uit af voor variatie
                $home = $i % 2 === 0 ? $teamA : $teamB;
                $away = $i % 2 === 0 ? $teamB : $teamA;

                $game = Game::updateOrCreate(
                    [
                        'home_team_id' => $home->id,
                        'away_team_id' => $away->id,
                        'starts_at'    => $startsAt,
                    ],
                    [
                        'location' => $venues[array_rand($venues)],
                        'status'   => 'scheduled',
                    ]
                );

                // Per game exact 1 openstaand verzoek
                MatchRequest::updateOrCreate(
                    ['game_id' => $game->id],
                    [
                        'created_by'      => $coach->id, // coach plaatst het verzoek (kan ook speler-id zijn)
                        'position_needed' => $positions[array_rand($positions)],
                        'players_needed'  => rand(1, 3),
                        'description'     => fake()->optional()->sentence(12),
                    ]
                );
            }

            $this->command?->info('✅ Demo-data: coach, 10 spelers, profielen, 2 teams, competities, 4 basiswedstrijden en 9 match requests.');
        });
    }
}
