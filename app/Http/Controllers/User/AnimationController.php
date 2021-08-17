<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Animation;
use App\AnimationControl;
use App\User;
use App\UserMetadata;
use App\Activity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AnimationController extends Controller
{
    public function add(Request $request){
        $validator = Validator::make($request->all(), [
           'name' => 'required|string',
           'css' => 'required|string',
        ]);

    if($validator->fails()){
        return response()->json(['message' => 'an error occured.']);
    }

    $user = auth()->user();

    $storageLeft = $user->membership->storage_limit - $user->membership->storage_used;

    if($storageLeft == 0 || $storageLeft < 0){

        return response()->json(['message' => $response]);

    }else{

    $user_animationscontrol = AnimationControl::where('user_id',$user->id)->firstOrFail();

    if($user_animationscontrol['daily_quota'] - $user_animationscontrol['total_created_today'] == 0 || $user_animationscontrol['daily_quota'] - $user_animationscontrol['total_created_today'] < 0){
        return response()->json(['message' => 'Error, You have reached your daily quota for saving animations.']);
    }else{

    $newanimation = new Animation;
    $newanimation['user_id'] = $user->id;
    $newanimation['name'] = $request->get('name');
    $newanimation['css'] = $request->get('css');
    $newanimation['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
    $newanimation->save();

    $user_animationscontrol->increment('total_created_today');
    $user_animationscontrol->save();

    $metadata = UserMetadata::where('user_id',$user->id)->first();

    if($metadata){
        $metadata->increment('total_animations');
    }else{
        $response = 'User md doesn\'t exist, please contact support.';
        return response()->json(['message' => $response]);
    }

    $newactivity = new Activity;
    $newactivity['user_id'] = $user->id;
    $newactivity['type'] = 'Saved '.$request->get('name').' (Animation) to storage.';
    $newactivity['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
    $newactivity->save();

    $user->membership->increment('storage_used');

    return response()->json(['message' => 'animation saved to your account.']);

    }

    }

    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
           'a_id' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'an error occured.']);
        }

        $user = auth()->user();
        $animation = Animation::where('id',$request->get('a_id'))->where('user_id',$user->id)->first();

        if($animation){
            if($request->has('name')){
                $animation['name'] = $request->get('name');
            }
            if($request->has('css')){
                $animation['css'] = $request->get('css');
            }
        }else{
            return response()->json(['message' => 'animation not found.']);
        }

        $newactivity = new Activity;
        $newactivity['user_id'] = $user->id;
        $newactivity['type'] = 'Updated '.$animation->name.' (Animation).';
        $newactivity['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $newactivity->save();

        $animation->save();
        return response()->json(['message' => 'animation updated successfully.']);

    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
           'a_id' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'an error occured.']);
        }

        $user = auth()->user();
        $animation = Animation::where('id',$request->get('a_id'))->first();

        if($animation){
            if($animation->user_id == $user->id){
                $metadata = UserMetadata::where('user_id',$user->id)->first();

                if($metadata){
                    $metadata->decrement('total_animations');
                }else{
                    $response = 'User md doesn\'t exist, please contact support.';
                    return response($response, 422);
                }

                $newactivity = new Activity;
                $newactivity['user_id'] = $user->id;
                $newactivity['type'] = 'Deleted '.$animation->name.' (Animation).';
                $newactivity['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
                $newactivity->save();

                $animation->delete();
                $user->membership->decrement('storage_used');
            }else{
                return response()->json(['message' => 'you do not have permissions to delete this animation.']);
            }
        }else{
            return response()->json(['message' => 'animation not found.']);
        }

        return response()->json(['message' => 'animation deleted successfully.']);
    }
}
