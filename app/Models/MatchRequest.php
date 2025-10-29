<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'created_by',
        'position_needed',
        'players_needed',
        'description',
        'is_active',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments()
    {
        return $this->hasMany(MatchComment::class);
    }
}
