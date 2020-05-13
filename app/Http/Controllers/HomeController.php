<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    private $key;

    public function __construct() {
        $this->key = env('RIOT_KEY');
    }

    public function home() {
        return view('home');
    }

    public function getSummonerInfo($name) {

        $client = new \GuzzleHttp\Client();
        $summRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/'.$name.'?api_key='.$this->key);
        $summInfo    = json_decode($summRequest->getBody()->getContents());

        $leagueRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/league/v4/entries/by-summoner/'.$summInfo->id.'?api_key='.$this->key);
        $leagueInfo    = json_decode($leagueRequest->getBody()->getContents());

        $profileIcon = $this->checkIfIconExist($summInfo->profileIconId);

        return view('summoner', [
            'summInfo' => $summInfo,
            'profileIcon' => $profileIcon
        ]);
    }

    public function checkIfIconExist($imgName) {
        $folder = 'profile_icons';
        $check = Storage::disk('public')->exists($folder.'/'.$imgName.'.png');

        if(!$check) {
            $url = 'http://ddragon.leagueoflegends.com/cdn/10.9.1/img/profileicon/'.$imgName.'.png';
            $contents = file_get_contents($url);
            $name = substr($url, strrpos($url, '/') + 1);
            Storage::disk('public')->put($folder.'/'.$name, $contents);
        }

        $imgName= $folder.'/'.$imgName.'.png';
        return $imgName;
    }
}
