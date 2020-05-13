<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
    protected $fillable = ['champ_id', 'key', 'name', 'image', 'version'];
}
