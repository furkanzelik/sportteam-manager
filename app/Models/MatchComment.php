<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_request_id',
        'user_id',
        'message',
    ];

    /**
     * Reactie hoort bij één match request
     */
    public function matchRequest()
    {
        return $this->belongsTo(MatchRequest::class);
    }

    /**
     * Reactie hoort bij één gebruiker
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
