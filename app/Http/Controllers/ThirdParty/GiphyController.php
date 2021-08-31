<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use GuzzleHttp\Client as Guzzle;

class GiphyController extends Controller
{
    public function search($query){
        $client = new Guzzle;
        $response = $client->request('GET','https://api.giphy.com/v1/gifs/search?api_key=ckdUJfg3AXYLXf8xZsVXfyQOUTKR3Mjl&q='.$query.'&limit=50&lang=en&fmt=json');
        $response = $response->getBody()->getContents();

        return response()->json($response);
    }
}
