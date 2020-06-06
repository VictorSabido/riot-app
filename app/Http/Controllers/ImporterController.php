<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Champion;
use Illuminate\Support\Facades\Storage;

class ImporterController extends Controller
{
    /**
     * Get the current champions by url
     *
     * @return void
     */
    public function getChampions() {
        $client = new \GuzzleHttp\Client();
        $championRequest = $client->request('GET', 'http://ddragon.leagueoflegends.com/cdn/10.9.1/data/en_US/champion.json');

        if($championRequest->getStatusCode() == 200){
            $champions = json_decode($championRequest->getBody()->getContents())->data;
            foreach($champions as $champion)
            {
                Champion::updateOrCreate(
                    ['key' => $champion->key],
                    [
                        'key'     => $champion->key,
                        'id_name'      => $champion->id,
                        'name'    => $champion->name,
                        'image'   => $champion->image->full,
                        'version' => $champion->version
                    ]
                );
            }

            return true;

        }else{
            throw new Exception('Unexpected response code ('.$championRequest->getStatusCode().'). Checkout https://developer.riotgames.com/docs/lol#data-dragon_champions');
        }
    }

    /**
     * Get the current image champions by url
     *
     * @return void
     */
    public function getChampionsImage() {
        $champions = Champion::get();
        $url = 'http://ddragon.leagueoflegends.com/cdn/10.10.3216176/img/champion/';
        $folder = 'champions';
        foreach($champions as $champ) 
        {
            $check = Storage::disk('public')->exists($folder.'/'.$champ->image);
            if(!$check) {
                $contents = file_get_contents($url.$champ->image);
                Storage::disk('public')->put($folder.'/'.$champ->image, $contents);
            }
        }

        return true;
    }


}
