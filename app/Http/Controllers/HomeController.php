<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $key;

    public function __construct() {
        $this->key = env('RIOT_KEY');
    }

    public function home() {
        // dd($this->key);
        // $client = new \GuzzleHttp\Client();
        // $res = $client->request('GET', 'https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/xReeak?api_key=RGAPI-ef434b5e-4a8e-4185-bf85-b1f23f3d822b');
        // $a = $res->getBody()->getContents();
        // echo $res->getStatusCode();

        // dd(json_decode($a));
        return view('home');
    }
}
