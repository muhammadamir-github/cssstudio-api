<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Element;
use App\User;
use App\UserMetadata;
use App\Activity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ElementController extends Controller
{
   public function add(Request $request){
        $validator = Validator::make($request->all(), [
           'name' => 'required|string',
           'css' => 'required|string',
           'type' => 'required|string',
        ]);

    if($validator->fails()){
        return response()->json(['message' => 'an error occured.']);
    }

    $user = auth()->user();

    $storageLeft = $user->membership->storage_limit - $user->membership->storage_used;

    if($storageLeft == 0 || $storageLeft < 0){

        $response = 'Storage is full.';
        return response()->json(['message' => $response]);

    }else{

    $newelement = new Element;
    $newelement['user_id'] = $user->id;
    $newelement['name'] = $request->get('name');
    $newelement['css'] = $request->get('css');
    $newelement['type'] = $request->get('type');
    $newelement['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
    $newelement->save();

    $metadata = UserMetadata::where('user_id',$user->id)->first();

    if($metadata){
        $metadata->increment('total_elements');
    }else{
        $response = 'User md doesn\'t exist, please contact support.';
        return response()->json(['message' => $response]);
    }

    $newactivity = new Activity;
    $newactivity['user_id'] = $user->id;
    $newactivity['type'] = 'Saved '.$request->get('name').' (Element) to storage.';
    $newactivity['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
    $newactivity->save();

    $user->membership->increment('storage_used');

    return response()->json(['message' => 'element saved to your account.']);

    }

    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
           'e_id' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'an error occured.']);
        }

        $user = auth()->user();
        $element = Element::where('id',$request->get('e_id'))->where('user_id',$user->id)->first();

        if($element){
            if($request->has('name')){
                $element['name'] = $request->get('name');
            }
            if($request->has('css')){
                $element['css'] = $request->get('css');
            }
        }else{
            return response()->json(['message' => 'element not found.']);
        }

        $element->save();

        $newactivity = new Activity;
        $newactivity['user_id'] = $user->id;
        $newactivity['type'] = 'Updated '.$element->name.' (Element).';
        $newactivity['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $newactivity->save();

        return response()->json(['message' => 'element updated successfully.']);

    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
           'e_id' => 'required|int',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'an error occured.']);
        }

        $user = auth()->user();
        $element = Element::where('id',$request->get('e_id'))->first();

        if($element){
            if($element->user_id == $user->id){
                
                $metadata = UserMetadata::where('user_id',$user->id)->first();

                if($metadata){
                    $metadata->decrement('total_elements');
                }else{
                    $response = 'User md doesn\'t exist, please contact support.';
                    return response($response, 422);
                }

                $newactivity = new Activity;
                $newactivity['user_id'] = $user->id;
                $newactivity['type'] = 'Deleted '.$element->name.' (Element).';
                $newactivity['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
                $newactivity->save();

                $element->delete();

                $user->membership->decrement('storage_used');
            }else{
                return response()->json(['message' => 'you do not have permissions to delete this element.']);
            }
        }else{
            return response()->json(['message' => 'element not found.']);
        }

        return response()->json(['message' => 'element deleted successfully.']);
    }
}
