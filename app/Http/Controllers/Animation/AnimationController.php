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
}
