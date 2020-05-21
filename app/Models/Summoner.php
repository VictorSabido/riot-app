<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\League;
use App\Models\Mastery;

class Summoner extends Model
{
    protected $fillable = ['summId', 'accountId', 'puuid', 'name', 'profileIconId', 'revisionDate', 'summonerLevel'];

    public function leagues() {
        return $this->hasMany(League::class, 'summoner_id');
    }

    public function masteries() {
        return $this->hasMany(Mastery::class, 'summoner_id')->orderBy('championPoints', 'desc');
    }

    public function getMasteries() {
        return $this->hasMany(Mastery::class, 'summoner_id')->limit(5)->orderBy('championPoints', 'desc');
    }

}
