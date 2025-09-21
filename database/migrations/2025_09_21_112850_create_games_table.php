<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // TIP: we maken hier de tabel 'matches' aan, ook al heet je model 'Game'
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('away_team_id')->constrained('teams')->cascadeOnDelete();
            $table->dateTime('starts_at');
            $table->string('location')->nullable();
            $table->string('status')->default('scheduled'); // scheduled|completed
            $table->timestamps();
        });

        // (optioneel) Als je per se een DB-check wilt EN je MySQL >= 8.0.16 is:
        // DB::statement('ALTER TABLE matches ADD CONSTRAINT chk_home_away CHECK (home_team_id <> away_team_id)');
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
