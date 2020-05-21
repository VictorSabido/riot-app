<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Champion;

class Mastery extends Model
{
    protected $fillable = ['summoner_id', 'champion_id', 'championLevel', 'championPoints', 'lastPlayTime', 'championPointsSinceLastLevel', 'championPointsUntilNextLevel', 'chestGranted', 'tokensEarned'];

    protected $appends = ['champion_image'];

    public function champion() {
        return $this->hasOne(Champion::class, 'key', 'champion_id');
    }

    public function getChampionImage() {
        return $this->champion->image;
    }
}
