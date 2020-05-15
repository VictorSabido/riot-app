<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Summoner extends Model
{
    protected $fillable = ['summId', 'accountId', 'puuid', 'name', 'profileIconId', 'revisionDate', 'summonerLevel'];
}
