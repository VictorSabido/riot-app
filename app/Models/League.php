<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $fillable = ['summoner_id', 'leagueId', 'queueType', 'tier', 'rank', 'leaguePoints', 'wins', 'losses', 'winRatio', 'veteran', 'inactive', 'freshBlood', 'hotStreak'];
}
