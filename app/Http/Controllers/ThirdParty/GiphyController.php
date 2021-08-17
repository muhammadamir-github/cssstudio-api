<?php

namespace App\Http\Controllers\ThirdParty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GiphyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gifs($keyword){
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET','https://api.giphy.com/v1/gifs/search?api_key=ckdUJfg3AXYLXf8xZsVXfyQOUTKR3Mjl&q='.$keyword.'&limit=50&lang=en&fmt=json');
        $response = $response->getBody()->getContents();

        return response()->json($response);
    }
}
