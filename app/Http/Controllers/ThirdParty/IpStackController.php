<?php

namespace App\Http\Controllers\ThirdParty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IpStackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function ipinfo($ip){

    $user = Auth::user();

    $client = new \GuzzleHttp\Client();
    $response = $client->request('GET', 'http://api.ipstack.com/'.$ip.'?access_key=cf2ab37c1c116d5ce6f4114d79927042');
    $response = $response->getBody()->getContents();

    //$response = $response['location']['country_flag'];

    return response()->json($response);

    }
}
