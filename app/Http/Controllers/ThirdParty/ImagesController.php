<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client as Guzzle;
use config;

class ImagesController extends Controller
{
    public function search($query){
        $urls = (array)[
            'https://api.giphy.com/v1/gifs/search?api_key='.config("app.GIPHY_API_KEY").'&q='.$query.'&limit=50&lang=en&fmt=json',
            'https://pixabay.com/api/?key='.config("app.PIXABAY_API_KEY").'&q='.$query,
            'https://api.unsplash.com/search/photos?client_id='.config("app.UNSPLASH_CLIENT_ID").'&page=1&per_page=50&query='.$query,
        ];

        $images = (array)[];

        $client = new Guzzle;
        foreach($urls as $url){
            $response = $client->request('GET', $url);
            $response = json_decode($response->getBody()->getContents(), true);

            if(isset($response["hits"])){
                $images = array_merge($images, $response["hits"]);
            }else{
                if(isset($response["results"])){
                    $images = array_merge($images, $response["results"]);
                }else{
                    if(isset($response["data"])){
                        $images = array_merge($images, $response["data"]);
                    }
                }
            }
        }

        return response()->json($images);
    }
}
