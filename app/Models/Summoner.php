<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\League;

class Summoner extends Model
{
    protected $fillable = ['summId', 'accountId', 'puuid', 'name', 'profileIconId', 'revisionDate', 'summonerLevel'];

    public function leagues() {
        return $this->hasMany(League::class, 'summoner_id');
    }
}
