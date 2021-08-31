<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client as Guzzle;

class GoogleFontsController extends Controller
{
    public function all(Request $request){
        $client = new Guzzle();
        $response = $client->request('GET', 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyBktJP9ry3C1ybMFUWrdZDs9Cj0ANM2Rq0');
        $response = $response->getBody()->getContents();

        return response()->json($response);
    }
}
