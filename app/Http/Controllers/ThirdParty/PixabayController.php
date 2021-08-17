<?php

namespace App\Http\Controllers\ThirdParty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PixabayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function images($keyword){
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET','https://pixabay.com/api/?key=12662507-ac77811ce4f187426a5446ca0&q='.$keyword);
        $response = $response->getBody()->getContents();

        return response()->json($response);
    }
}
