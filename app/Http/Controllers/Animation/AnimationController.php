<?php

namespace App\Http\Controllers\Animation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Animation\Animation;

class AnimationController extends Controller
{
    public function readyMadeAnimations(){
        $animations = Animation::where('user_id', 0)->get();
        return response()->json(['success' => $animations], 200);
    }

    public function get($name){
        $animation = Animation::where('name', $name)->first();

        if($animation){
           return response()->json(['css' => $animation->css]);
        }else{
           return response()->json(['message' => 'Animation not found.']);
        }
    }
}
