<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Models\Champion;

class ImporterController extends Controller
{
    public function getChampions() {
        $client = new \GuzzleHttp\Client();
        $championRequest = $client->request('GET', 'http://ddragon.leagueoflegends.com/cdn/10.9.1/data/en_US/champion.json');

        if($championRequest->getStatusCode() == 200){
            $champions = json_decode($championRequest->getBody()->getContents())->data;
            foreach($champions as $champion)
            {
                Champion::updateOrCreate(
                    ['champ_id' => $champion->id],
                    [
                        'champ_id' => $champion->id,
                        'key'      => $champion->key,
                        'name'     => $champion->name,
                        'image'    => $champion->image->full,
                        'version'  => $champion->version
                    ]
                );
            }

            return true;

        }else{
            throw new Exception('Unexpected response code ('.$championRequest->getStatusCode().'). Checkout https://developer.riotgames.com/docs/lol#data-dragon_champions');
        }
    }
}
