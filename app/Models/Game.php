<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{

    // relaties van de teams (erd)
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    use HasFactory;

    protected $table = 'matches'; // geeft aan dat het hoort bij de matches tabel

    // bepaalt welke kolommen er zijn
    protected $fillable = ['home_team_id','away_team_id','starts_at','location','status'];
    protected $casts = ['starts_at' => 'datetime'];
}


