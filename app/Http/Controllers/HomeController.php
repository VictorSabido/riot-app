<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Summoner;
use App\Models\History;
use App\Models\League;
use App\Models\Champion;
use App\Models\Mastery;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct() {
        $this->key = $this->getApiKey();
        $this->guzzOptions = $this->getGuzzleOptions();
    }

    public function home() {

        return view('home');
    }

    public function getSummonerInfo($name) {

        $this->checkSummonerDatabase($name);

        $summoner = Summoner::with(['leagues', 'getMasteries'])->where('name',  $name)->first();

        $order = ['RANKED_SOLO_5x5', 'RANKED_FLEX_SR'];
        $summoner->leagues = $summoner->leagues->sort(function ($a, $b) use ($order) {
            $pos_a = array_search($a->queueType, $order);
            $pos_b = array_search($b->queueType, $order);
            return $pos_a - $pos_b;
        });

        $this->checkIconId($summoner->profileIconId);

        $history = History::orderBy('created_at', 'desc')->take(4)->get();

        $matchs = app('App\Http\Controllers\MatchController')->getMatchsHistory($summoner->accountId);

        return view('summoner', [
            'summ'    => $summoner,
            'history' => $history,
            'matchs'  => $matchs
        ]);
    }

    public function updateSummoner($name) {
        $this->updateSummonerData($name);

        return redirect()->back();
    }

    public function updateSummonerData($name) {
        $summonerInfo    = $this->getSummoner($name);
        $summonerUpdated = $this->saveSummoner($summonerInfo);

        $leagueInfo = $this->getLeagues($summonerUpdated);
        $this->saveLeagues($leagueInfo,$summonerUpdated);

        $masteries = $this->getMasteries($summonerUpdated);
        $this->saveMasteries($masteries, $summonerUpdated);
    }

    private function checkIconId($imgName) {
        $folder = 'profile_icons';
        $check = Storage::disk('public')->exists($folder.'/'.$imgName.'.png');

        if(!$check) {
            $url = 'http://ddragon.leagueoflegends.com/cdn/10.10.3208608/img/profileicon/'.$imgName.'.png';
            $contents = file_get_contents($url);
            $name = substr($url, strrpos($url, '/') + 1);
            Storage::disk('public')->put($folder.'/'.$name, $contents);
        }

        $imgName= $folder.'/'.$imgName.'.png';
        return $imgName;
    }

    private function checkSummonerDatabase($name) {
        $summoner = Summoner::with(['leagues', 'getMasteries'])->where('name', $name)->first();
        if($summoner == null) {
            $this->updateSummonerData($name);
        } else if((count($summoner->leagues) > 0) && (count($summoner->getMasteries) > 0)) {
            $this->updateSummonerData($name);
        }
    }


    public function getSummoner($name) {
        $client = new \GuzzleHttp\Client();
        $summRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/'.$name.'?api_key='.$this->key, $this->guzzOptions);

        if($summRequest->getStatusCode() != 200) {
            throw new \Exception('Error Processing Request API RIOT. '.$summRequest->getReasonPhrase().', code: '. $summRequest->getStatusCode(), 1);
        }

        $summRequest = json_decode($summRequest->getBody()->getContents());

        return $summRequest;
    }


    public function saveSummoner($summRequest) {
        $summoner= Summoner::updateOrCreate(
            ['accountId' => $summRequest->accountId],
            [
                'summId'        => $summRequest->id,
                'accountId'     => $summRequest->accountId,
                'puuid'         => $summRequest->puuid,
                'name'          => $summRequest->name,
                'profileIconId' => $summRequest->profileIconId,
                'revisionDate'  => $summRequest->revisionDate,
                'summonerLevel' => $summRequest->summonerLevel,
                'updated_at'    => Carbon::now()
            ]
        );

        return $summoner;
    }

    private function saveInHistory($summoner) {
        $history = new History;
        $history->summoner_id = $summoner->summId;
        $history->name        = $summoner->name;

        $history->save();
    }

    private function getLeagues($summoner) {
        $client = new \GuzzleHttp\Client();
        $leagueRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/league/v4/entries/by-summoner/'.$summoner->summId.'?api_key='.$this->key);
        $leagueInfo    = json_decode($leagueRequest->getBody()->getContents());

        return $leagueInfo;
    }

    private function saveLeagues($leagueInfo, $summoner) {

        foreach($leagueInfo as $rank) {
            $winRatio  = round(($rank->wins / ($rank->wins + $rank->losses)) * 100);

            League::updateOrCreate(
                ['summoner_id' => $summoner->id, 'queueType' => $rank->queueType],
                [
                    'summoner_id'  => $summoner->id,
                    'leagueId'     => $rank->leagueId,
                    'queueType'    => $rank->queueType,
                    'tier'         => $rank->tier,
                    'rank'         => $rank->rank,
                    'leaguePoints' => $rank->leaguePoints,
                    'wins'         => $rank->wins,
                    'losses'       => $rank->losses,
                    'winRatio'     => $winRatio,
                    'veteran'      => $rank->veteran,
                    'inactive'     => $rank->inactive,
                    'freshBlood'   => $rank->freshBlood,
                    'hotStreak'    => $rank->hotStreak,
                ]
            );
        }
    }

    private function getMasteries($summoner) {
        $this->checkChampions();

        $client = new \GuzzleHttp\Client();
        $masteryRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/'.$summoner->summId.'?api_key='.$this->key);
        $masteryInfo    = json_decode($masteryRequest->getBody()->getContents());

        return $masteryInfo;
    }

    private function saveMasteries($masteryInfo, $summoner) {
        foreach($masteryInfo as $mastery) {
            Mastery::updateOrCreate(
                ['summoner_id' => $summoner->id, 'champion_id' => $mastery->championId],
                [
                    'summoner_id' => $summoner->id,
                    'champion_id' => $mastery->championId,
                    'championLevel' => $mastery->championLevel,
                    'championPoints' => $mastery->championPoints,
                    'lastPlayTime' => $mastery->lastPlayTime,
                    'championPointsSinceLastLevel' => $mastery->championPointsSinceLastLevel,
                    'championPointsUntilNextLevel' => $mastery->championPointsUntilNextLevel,
                    'chestGranted' => $mastery->chestGranted,
                    'tokensEarned' => $mastery->tokensEarned,
                ]
            );
        }
    }

    private function checkChampions() {
        $champions = Champion::first();
        if($champions == null) {
            app('App\Http\Controllers\ImporterController')->getChampions();
            app('App\Http\Controllers\ImporterController')->getChampionsImage();
        }
    }
}
