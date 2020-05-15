<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mastery extends Model
{
    protected $fillable = ['summoner_id', 'champion_id', 'championLevel', 'championPoints', 'lastPlayTime', 'championPointsSinceLastLevel', 'championPointsUntilNextLevel', 'chestGranted', 'tokensEarned'];
}
