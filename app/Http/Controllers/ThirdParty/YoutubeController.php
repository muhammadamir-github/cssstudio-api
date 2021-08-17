<?php

namespace App\Http\Controllers\ThirdParty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class YoutubeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function videos($keyword){
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET','https://www.googleapis.com/youtube/v3/search?part=snippet&order=viewCount&type=video&maxResults=5&key=AIzaSyAtgk_33_zL0RkGKLFDUguURDkuy4cGPpk&q='.$keyword);
        $response = $response->getBody()->getContents();

        return response()->json($response);
    }

    public function videoMeta($id){
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET','https://www.googleapis.com/youtube/v3/videos?part=statistics&key=AIzaSyAtgk_33_zL0RkGKLFDUguURDkuy4cGPpk&id='.$id);
        $response = $response->getBody()->getContents();

        return response()->json($response);
    }
}
