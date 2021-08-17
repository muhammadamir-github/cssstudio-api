<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserMetadata;
use App\Activity;
use App\UserPersonal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PersonalController extends Controller
{
    public function update(Request $request){
        $user = auth()->user();

        if($user){

            $userPersonal = UserPersonal::where('user_id',$user->id)->first();
            if($userPersonal){

                if($request->has('phone')){
                    
                    $userPersonal['phone'] = $request->get('phone');
                    
                    $newActivity = new Activity;
                    $newActivity['user_id'] = $user->id;
                    $newActivity['type'] = 'Updated phone number to '.$request->get('phone');
                    $newActivity['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
                    $newActivity->save();

                }

                $userPersonal->save();
                return response()->json(['message' => 'Your personal data has been safely updated.']);

            }
        }

    }
}
