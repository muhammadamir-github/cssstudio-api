<?php

namespace App\Http\Controllers\ThirdParty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client as Guzzle;

use config;

class GoogleFontsController extends Controller
{
    public function all(Request $request){
        $client = new Guzzle();
        $response = $client->request('GET', 'https://www.googleapis.com/webfonts/v1/webfonts?key='.config("app.GOOGLE_API_KEY"));
        $response = $response->getBody()->getContents();

        return response()->json($response);
    }
}
