<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Champion extends Model
{
    protected $fillable = ['key', 'id_name', 'name', 'image', 'version'];

    protected $primaryKey = 'key';
    public $incrementing = false;
}
