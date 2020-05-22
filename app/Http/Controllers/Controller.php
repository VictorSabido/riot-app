<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $key;
    private $guzzOptions = ['http_errors' => false];

    public function getApiKey() {
        $this->key = env('RIOT_KEY');
        return $this->key;
    }

    public function getGuzzleOptions() {
        return $this->guzzOptions;
    }
}
