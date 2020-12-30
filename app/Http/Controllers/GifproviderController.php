<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gifprovider;
use App\Models\Keyword;
use App\Models\GifproviderKeyword;
use App\Models\Configuration;
use Illuminate\Support\Facades\Cache;

class GifproviderController extends Controller
{
    public function getMaxResults()
    {
        $config = Configuration::find(1);
        return $config->max_results;
    }

    public function setMaxResults($maxResults)
    {
        $config = Configuration::find(1);
        $config->max_results = $maxResults;
        $config->save();
    }

    public function getProviderId()
    {
        $config = Configuration::find(1);
        return $config->provider;
    }

    public function setProviderId($id)
    {
        if(Gifprovider::where('slug', $id)->count()>0)
        {
            $identifier = Gifprovider::where('slug', $id)->first()->id;
            $config = Configuration::find(1);
            $config->provider = $identifier;
            $config->save();
            $this->flushCache();
            return response('', 204)
                ->header('Content-Type', 'text/plain');
        }
        else
            return response('', 404)
                ->header('Content-Type', 'text/plain');
        
    }

    public function addGifprovider()
    {
        $gifproviders=[
            [
                "slug" => "giphy",
                "description" => "hello there",
                "counter" => 0,
                "credentials" => "{\"username\": \"Hello World\",\"password\": \"così bella in chiaro\"}"
            ],
            [
                "slug" => "tenor",
                "description" => "hello folks",
                "counter" => 0,
                "credentials" => "{\"username\": \"Ciao Pippo\",\"password\": \"anche questa in chiaro\"}"
            ]
        ];

        Gifprovider::insert($gifproviders);
        return "Gifproviders are created successfully!";
    }

    public function addKeyword()
    {
        $keyword = new Keyword();
        $keyword->value = "duck";
        $keyword->save();

        $gifproviderids = [1];
        $keyword->gifproviders()->attach($gifproviderids);
        return "Record has been created successfully";
    }

    public function getProviders()
    {
        $array_providers = array();
        $providers = GifProvider::all();
        foreach ($providers as $p)
        {
            $array_providers[] =
            [
                "identifier" => $p->slug,
                "description" => $p->description,
                "calls" => $p->counter,
            ];
        }

        $array = ["providers" => $array_providers];
        return json_encode($array);
    }

    public function getStatsById($identifier)
    {
        if (Gifprovider::where('slug', $identifier)->count() > 0)
        {
            $id = Gifprovider::where('slug', $identifier)->first()->id;
            $gifprovider = Gifprovider::find($id);

            $array_keywords = array();
            $calls = $gifprovider->counter;
            $keywords = $this->getAllKeywordsByProvider($id);
            $counter = 0;
            foreach ($keywords as $k)
            {
                $counter = GifproviderKeyword::where('keyword_id', $k->id)->where('gifprovider_id', $gifprovider->id)->first()->counter;
                $array_keywords[] =
                [
                    "keyword" => $k->value,
                    "calls" => $counter,
                ];
            }
            $array = 
            [
                "calls" => $calls,
                "keywords" => $array_keywords,
            ];
            return json_encode($array);
        }
        else
            return response('', 404)
                ->header('Content-Type', 'text/plain');
    }

    public function getAllKeywordsByProvider($identifier)
    {
        $gifprovider = Gifprovider::find($identifier);
        $keywords = $gifprovider->keywords;
        return $keywords;
    }

    public function getGifs($keyword)
    {
        $providerId = $this->getProviderId();
        $keyword = $this->keywordBeautify($keyword);
        
        $provider = Gifprovider::find($providerId);
        $provider->counter += 1;
        $provider->save();

        if(Keyword::where('value', $keyword)->first()->id)
        {
            $idKey = Keyword::where('value', $keyword)->first()->id;
            $arraylist = GifproviderKeyword::where('keyword_id', $idKey)->get();
            foreach($arraylist as $al)
            {
                $obj = GifproviderKeyword::find($al->id);
                $obj->counter += 1;
                $obj->save();
            }
        }
        $config = Configuration::find(1);
        if (!Cache::has('item')) 
        {
            $config->cc = 0;
            $config->save();
        }
        $cc = $config->cc;
        //costruttore di risultati, simulando una richiesta API al servizio
        //se la variabile d'appoggio CC è 0, vuol dire che è la prima richiesta di informazioni, nessun dato è in cache
        if ($cc == 0)
        {
            return $this->getContentByProvider($providerId);
        }
        else
        {
            //in questo caso la variabile d'appoggio CC è diversa da 0, i dati sono salvati in cache, quindi ritorno la cache
            return Cache::get('item');
        }
    }

    public function getGifsStats($keyword)
    {
        $keyword = $this->keywordBeautify($keyword);
        
        if (Keyword::where('value', $keyword)->count() > 0)
        {
            $idKey = Keyword::where('value', $keyword)->first()->id;
            $arraylist = GifproviderKeyword::where('keyword_id', $idKey)->get();
            $array_stats=array();
            foreach($arraylist as $al)
            {
                $providerName = Gifprovider::where('id', $al->gifprovider_id)->first()->slug;
                $arr = array($providerName => $al->counter);
                $array_stats = $array_stats + $arr;
            }
            $array = 
            [
                "stats" => $array_stats,
            ];

            return json_encode($array);
        }
        else
            return response('', 404)
                ->header('Content-Type', 'text/plain');

        
    }

    public function keywordBeautify($keyword)
    {
        $keyword = str_replace("_", " ", $keyword);
        if (strpos($keyword, "  ")!== false)
        {
            $keyword = str_replace("  ", " ", $keyword);
        }
        $keyword = strtolower($keyword);
        return $keyword;
    }

    public function flushCache()
    {
        $config = Configuration::find(1);
        $config->cc = 0;
        $config->save();
        Cache::forget('item');
        echo ("Cache Cleared");
    }

    public function getContentByProvider($providerId)
    {

        $max_results = $this->getMaxResults();
        $array = [];

        $config = Configuration::find(1);
        
        //creo le stringhe
        if($providerId == 1)
        {
            for($i = 1; $i<=$max_results; $i++)
            {
                array_push($array, "www.giphy.com/animation".$i.".gif");
            }
            $array_results = ["results" => $array];
            $json = json_encode($array_results);
            Cache::put('item', $json, now()->addMinutes(360));
            $config->cc = 1;
            $config->save();
            return $json;
        }
        elseif($providerId == 2)
        {
            for($i = 1; $i<=$max_results; $i++)
            {
                array_push($array, "www.tenor".$i.".gif");
            }
            $array_results = ["results" => $array];
            $json = json_encode($array_results);
            Cache::put('item', $json, now()->addMinutes(360));
            $config->cc = 1;
            $config->save();
            return $json;
        }
    }
}
