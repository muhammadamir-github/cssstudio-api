<?php

namespace App\Http\Controllers\ThirdParty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UnsplashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function images($keyword){
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET','https://api.unsplash.com/search/photos?client_id=158678700c090bc8ca3abf975ffbe2f5377b27b8d980d7a543876db2148b7871&page=1&per_page=50&query='.$keyword);
        $response = $response->getBody()->getContents();

        return response()->json($response);
    }
}
