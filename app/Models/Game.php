<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Game extends Model
{

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    use HasFactory;

    protected $table = 'matches';
    protected $fillable = ['home_team_id','away_team_id','starts_at','location','status'];
    protected $casts = ['starts_at' => 'datetime'];
}


