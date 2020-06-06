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

    /**
     * Return to searcher
     *
     * @return View
     */
    public function home() {

        return view('home');
    }

    /**
     * UShow the summoner's summary collecting the data by name
     *
     * @param [String] $name
     * @return View
     */
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

    /**
     * Function that updates the summoner data and returns you to the view
     *
     * @param [String] $name
     * @return void
     */
    public function updateSummoner($name) {
        $this->updateSummonerData($name);

        return redirect()->back();
    }

    /**
     * Updates all grouped data of the summoner
     *
     * @param [String] $name
     * @return void
     */
    public function updateSummonerData($name) {
        $summonerInfo    = $this->getSummoner($name);
        $summonerUpdated = $this->saveSummoner($summonerInfo);

        $leagueInfo = $this->getLeagues($summonerUpdated);
        $this->saveLeagues($leagueInfo,$summonerUpdated);

        $masteries = $this->getMasteries($summonerUpdated);
        $this->saveMasteries($masteries, $summonerUpdated);
    }

    /**
     * Look for the summoner icon and if it can't find it, download
     *
     * @param [String] $imgName
     * @return void
     */
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

    /**
     * Check if the invoker exists if it is not saved and if it is old update it
     *
     * @param [String] $name
     * @return void
     */
    private function checkSummonerDatabase($name) {
        $summoner = Summoner::with(['leagues', 'getMasteries'])->where('name', $name)->first();
        if($summoner == null) {
            $this->updateSummonerData($name);
        } else if((count($summoner->leagues) > 0) && (count($summoner->getMasteries) > 0)) {
            $now = Carbon::now();
            $updated_at = $summoner->updated_at;
            $diff = ($updated_at->diffInHours($now, true));

            if($diff > 2) {
                $this->updateSummonerData($name);
            }
        }
    }

    /**
     * Get summoner information
     *
     * @param [type] $name
     * @return void
     */
    public function getSummoner($name) {
        $client = new \GuzzleHttp\Client();
        $summRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/'.$name.'?api_key='.$this->key, $this->guzzOptions);

        if($summRequest->getStatusCode() != 200) {
            throw new \Exception('Error Processing Request API RIOT. '.$summRequest->getReasonPhrase().', code: '. $summRequest->getStatusCode(), 1);
        }

        $summRequest = json_decode($summRequest->getBody()->getContents());

        return $summRequest;
    }

    /**
     * Set summoner's information in database
     *
     * @param [Obj] $summRequest
     * @return void
     */
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

    /**
     * Save summoner in history 
     *
     * @param [Obj] $summoner
     * @return void
     */
    private function saveInHistory($summoner) {
        $history = new History;
        $history->summoner_id = $summoner->summId;
        $history->name        = $summoner->name;

        $history->save();
    }

    /**
     * Get the summoner's rank and leagues
     *
     * @param [Obj] $summoner
     * @return void
     */
    private function getLeagues($summoner) {
        $client = new \GuzzleHttp\Client();
        $leagueRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/league/v4/entries/by-summoner/'.$summoner->summId.'?api_key='.$this->key);
        $leagueInfo    = json_decode($leagueRequest->getBody()->getContents());

        return $leagueInfo;
    }

    /**
     * Set summoner league in database in database
     *
     * @param [Obj] $leagueInfo
     * @param [Obj] $summoner
     * @return void
     */
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

    /**
     * Get the summoner's masteries
     *
     * @param [Obj] $summoner
     * @return void
     */
    private function getMasteries($summoner) {
        $this->checkChampions();

        $client = new \GuzzleHttp\Client();
        $masteryRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/'.$summoner->summId.'?api_key='.$this->key);
        $masteryInfo    = json_decode($masteryRequest->getBody()->getContents());

        return $masteryInfo;
    }

    /**
     * Set summoner's masteries in database
     *
     * @param [type] $masteryInfo
     * @param [type] $summoner
     * @return void
     */
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

    /**
     * Check if the champions exist in the database
     *
     * @return void
     */
    private function checkChampions() {
        $champions = Champion::first();
        if($champions == null) {
            app('App\Http\Controllers\ImporterController')->getChampions();
            app('App\Http\Controllers\ImporterController')->getChampionsImage();
        }
    }
}
