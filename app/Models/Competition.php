<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Competition extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Relatie: een competitie heeft veel teams.
     */
    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
