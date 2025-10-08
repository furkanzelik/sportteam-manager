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

    public function matchRequest()
    {
        return $this->belongsTo(MatchRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
