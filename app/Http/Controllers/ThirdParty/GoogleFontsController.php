<?php

namespace App\Http\Controllers\ThirdParty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoogleFontsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function fonts(){

    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyBktJP9ry3C1ybMFUWrdZDs9Cj0ANM2Rq0');
    $response = $response->getBody()->getContents();

    return response()->json($response);

    }
    
}
