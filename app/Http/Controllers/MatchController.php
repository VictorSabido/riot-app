<?php

namespace App\Http\Controllers;

use App\Models\Champion;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function __construct() {
        $this->key = $this->getApiKey();
        $this->guzzOptions = $this->getGuzzleOptions();
    }

    public function getMatchsHistory($encryptedAccountId) {
        $client = new \GuzzleHttp\Client();
        $summRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/match/v4/matchlists/by-account/'.$encryptedAccountId.'?api_key='.$this->key, $this->guzzOptions);
        $summRequest = json_decode($summRequest->getBody()->getContents());

        $infoMatch = [];
        $totalMatch = 5;
        for ($i=0; $i < $totalMatch; $i++) { 
            $gameId = $summRequest->matches[$i]->gameId;
            
            $matchRequest = $client->request('GET', 'https://euw1.api.riotgames.com/lol/match/v4/matches/'.$gameId.'?api_key='.$this->key, $this->guzzOptions);
            $match = json_decode($matchRequest->getBody()->getContents());

                foreach($match->teams as $team) {
                    if($team->win == 'Win') {
                        $infoMatch[$match->gameId]['teamWinner'] = $team->teamId;
                    }
                }
        
            foreach($match->participants as $participant) {
                $participantId = $participant->participantId;
                foreach($match->participantIdentities as $participantDeails) 
                {
                    if($participantDeails->participantId == $participantId) {
                        $summonerName = $participantDeails->player->summonerName;
                        $requestedSummoner = $participantDeails->player->accountId;
                        
                        if($encryptedAccountId == $requestedSummoner) {
                            if($participant->teamId == $infoMatch[$match->gameId]['teamWinner']) {
                                $infoMatch[$match->gameId]['winner'] = true;
                                $infoMatch[$match->gameId]['bgColor'] = 'light-blue accent-1';
                            } else {
                                $infoMatch[$match->gameId]['winner'] = false;
                                $infoMatch[$match->gameId]['bgColor'] = 'red accent-1';
                            }

                            $infoMatch[$match->gameId]['champion'] = Champion::where('key', $participant->championId)->first()->image;
                            $infoMatch[$match->gameId]['stats'] = [
                                'kills' => $participant->stats->kills,
                                'deaths' => $participant->stats->deaths,
                                'assists' => $participant->stats->assists,
                                'champLevel' => $participant->stats->champLevel,
                            ];
                        }
                    }
                }

                $champ = Champion::where('key', $participant->championId)->first();
                $infoMatch[$match->gameId]['teams'][$participant->teamId][] = [
                        'participantId' => $participantId,
                        'summonerName' => $summonerName,
                        'requestedSummoner' => ($encryptedAccountId == $requestedSummoner) ? true : false,
                        'championId' => $participant->championId,
                        'championImage' => $champ->image,
                        'championName' => $champ->name,
                    ];
            }
    
            $infoMatch[$match->gameId]['gameDuration'] = $this->conversorSegundosHoras($match->gameDuration);
            // dd($match);
        }

        // dd($encryptedAccountId,$summRequest,$infoMatch);
        return $infoMatch;
    }

    function conversorSegundosHoras($tiempo_en_segundos) {
        $horas = floor($tiempo_en_segundos / 3600);
        $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
        $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);
    
        $hora_texto = "";
        if ($horas > 0 ) {
            $hora_texto .= $horas . "h ";
        }
    
        if ($minutos > 0 ) {
            $hora_texto .= $minutos . "m ";
        }
    
        if ($segundos > 0 ) {
            $hora_texto .= $segundos . "s";
        }
    
        return $hora_texto;
    }


}
