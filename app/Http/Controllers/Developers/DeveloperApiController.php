<?php

namespace App\Http\Controllers\Developers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Element;


class DeveloperApiController extends Controller
{
    public function style($name,$key){
        $element = Element::where('name',$name)->firstOrFail();
        return response()->json(['success' => $element]);
    }
}
