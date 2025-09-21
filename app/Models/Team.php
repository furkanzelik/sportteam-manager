<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Team extends Model
{
    use HasFactory;

    // Mass assignment (welke velden je via create/update mag invullen)
    protected $fillable = [
        'name',
        'coach_id',
        'competition_id',
    ];

    /**
     * Relatie: een team hoort bij één coach (User).
     */
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    /**
     * Relatie: een team hoort bij één competitie.
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    /**
     * Relatie: een team heeft veel spelers (Users).
     * Dit gaat via de pivot tabel 'team_user'.
     */
    public function players()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Relatie: een team speelt veel wedstrijden als thuisteam.
     */
    public function homeMatches()
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    /**
     * Relatie: een team speelt veel wedstrijden als uitteam.
     */
    public function awayMatches()
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }
}
