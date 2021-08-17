<?php

namespace App\Http\Controllers\Storage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Animation;

class AnimationsController extends Controller
{
   public function animation($name){
     $user = auth()->user();
     $animation = Animation::where('name',$name)->first();

     if($animation){
        return response()->json(['css' => $animation->css]);
     }else{
        return response()->json(['message' => 'Animation not found.']);
     }
   }
}
