<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;
use App\User;
use App\SignupKey;
use App\LoginHistory;
use App\UserMetadata;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Mail;

class LoginController extends Controller
{
    public function login(Request $request) {

        $validator = Validator::make($request->all(), [
              'i' => 'required|string',
              'la' => 'required|string',
              'lo' => 'required|string',
              'la' => 'required|string',
              'f' => 'required|string',
              'email' => 'required|string',
              'password' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'an error occured.']);
        }

        $user = User::where('email', $request->email)->first();
        
        if($user){
            if($user['type'] == 'Admin'){
                $response = ['message' => 'Admins can not login.'];
                return response($response);
            }else{

        	if($user['verified'] == '1'){
            if($user['deactivated'] == '0'){
              if ($request->password == $user->password) {

                $userTokens = $user->tokens;

                foreach($userTokens as $t) {
                   $t->revoke();  
                   $t->delete();   
                }

                $token = $user->createToken('Laravel Personal Access Client')->accessToken;

                $response = ['message' => 'Logged in successfully!' , 'accessToken' => $token];

                $newLoginHistory = New LoginHistory;
                $newLoginHistory['user_id'] = $user->id;
                $newLoginHistory['ip_address'] = $request->get('i');
                $newLoginHistory['latitude'] = $request->get('la');
                $newLoginHistory['longitude'] = $request->get('lo');
                $newLoginHistory['country'] = $request->get('c');
                $newLoginHistory['flag'] = $request->get('f');
                $newLoginHistory['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
                $newLoginHistory->save();

                $metadata = UserMetadata::where('user_id',$user->id)->first();

                if($metadata){
                  $metadata->increment('total_logins');
                }else{
                  $response = ['message' => 'User md doesn\'t exist, please contact support.'];
                  return response($response);
                }

                return response($response);
            } else {
                $response = ['message' => 'Unauthorized'];
                return response($response);
            }

            }else{
              $response = ['message' => 'Deactivated'];
              return response($response);
            }

        	}else{

            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_';
            $charactersLength = strlen($characters);
            $skey = '';
            for ($i = 0; $i < 50; $i++) {
                $skey .= $characters[rand(0, $charactersLength - 1)];
            }

            }

            $newKeyEntry = new SignupKey;
            $newKeyEntry['key'] = $skey;
            $newKeyEntry['user_id'] = $user->id;
            $newKeyEntry['used'] = 0;
            $newKeyEntry['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
            $newKeyEntry->save();

            $user->signupkey->delete();

            $link = 'https://api.cssstudio.co/me/validation/signup/'.$skey;
    
            $to_name = $user->username;
            $to_email = $user->email;
            $data = array('username'=>$to_name, "link" => $link);
    
            Mail::send(['html' => 'emails.signup'], $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                        ->subject('Confirm your email and get started.');
                $message->from('no-reply@cssstudio.co','CSS Studio');
            });

        		$response = ['message' => 'Please verify your account to continue.'];
        		return response($response);
        	}
            
        } else {
            $response = ['message' => 'User doesn\'t exist'];
            return response($response, 422);
        }
        
    }

    public function admin_login(Request $request){

     $validator = Validator::make($request->all(), [
              'googleauthcode' => 'required|string',
              'email' => 'required|string',
              'password' => 'required|string',
        ]);

    if($validator->fails()){
            return response()->json(['message' => 'an error occured.']);
    }

    $user = User::where('email', $request->email)->first();

    if($user){
        if($user['verified'] == '1'){
            if($user['deactivated'] == '0'){
                if($user['type'] == 'Admin'){
                    if ($request->password == $user->password) {

                     $userTokens = $user->tokens;

                     foreach($userTokens as $t) {
                       $t->revoke();  
                       $t->delete();   
                     }

                     $token = $user->createToken('Laravel Personal Access Client')->accessToken;
                     $response = ['message' => 'Logged in successfully!' , 'accessToken' => $token];

                     $newLoginHistory = New LoginHistory;
                     $newLoginHistory['user_id'] = $user->id;
                     $newLoginHistory['ip_address'] = '';
                     $newLoginHistory['latitude'] = '';
                     $newLoginHistory['longitude'] = '';
                     $newLoginHistory['country'] = '';
                     $newLoginHistory['flag'] = '';
                     $newLoginHistory['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
                     $newLoginHistory->save();

                     $metadata = UserMetadata::where('user_id',$user->id)->first();

                     if($metadata){
                       $metadata->increment('total_logins');
                     }else{
                       $response = ['message' => 'User md doesn\'t exist, please contact support.'];
                       return response($response);
                     }

                     return response($response);

                    }
                }else{
                    $response = ['message' => 'Users can not login.'];
                    return response($response);
                }
            }
        }
    }

    }

}
