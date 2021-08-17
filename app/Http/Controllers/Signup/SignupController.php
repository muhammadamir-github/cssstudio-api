<?php

namespace App\Http\Controllers\Signup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\LoginHistory;
use App\UserMetadata;
use App\UserPersonal;
use App\MembershipMeta;
use App\Activity;
use App\SignupKey;
use App\AnimationControl;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SignupController extends Controller
{
    public function signup(Request $request){

        //return response()->json(['message' => 'Signups are put on hold for sometime.']);

        $validator = Validator::make($request->all(),[

            'username' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string',
            't' => 'required|string',
            'i' => 'required|string',
            'cy' => 'required|string',
            'cty' => 'required|string',
            'ctyc' => 'required|string',
            'cnt' => 'required|string',
            'cntc' => 'required|string',
            'r' => 'required|string',
            'rc' => 'required|string',
            'z' => 'required|string',
            'la' => 'required|string',
            'lo' => 'required|string',
            'f' => 'required|string',

        ]);

        if($validator->fails()){
            return response()->json(['message' => 'an error occured']);
        }

        $emailUniqueTest = User::where('email',$request->get('email'))->first();

        if($emailUniqueTest !== NULL){
            return response()->json(['message' => 'Email address already registered with us.']);
        }

        $newUser = new User;
        $newUser['username'] = $request->get('username');
        $newUser['email'] = $request->get('email');
        $newUser['password'] = $request->get('password');
        $newUser['ip_address'] = $request->get('i');
        $newUser['type'] = 'Free';

        $newUser['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $newUser['last_login'] = \Carbon\Carbon::now()->toDateTimeString();
        $newUser['verified'] = 0;
        $newUser['expires_at'] = \Carbon\Carbon::now()->addDays(3)->toDateTimeString();
        $newUser->save();

        $newUserMetadata = New UserMetadata;
        $newUserMetadata['user_id'] = $newUser->id;
        $newUserMetadata['total_animations'] = '0';
        $newUserMetadata['total_elements'] = '0';
        $newUserMetadata['total_logins'] = '1';
        $newUserMetadata['total_spending'] = '0';
        $newUserMetadata['total_payments'] = '0';
        $newUserMetadata->save();

        $newLoginHistory = New LoginHistory;
        $newLoginHistory['user_id'] = $newUser->id;
        $newLoginHistory['ip_address'] = $request->get('i');
        $newLoginHistory['latitude'] = $request->get('la');
        $newLoginHistory['longitude'] = $request->get('lo');
        $newLoginHistory['country'] = $request->get('cty');
        $newLoginHistory['flag'] = $request->get('f');
        $newLoginHistory['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $newLoginHistory->save();

        $newUserPersonal = New UserPersonal;
        $newUserPersonal['user_id'] = $newUser->id;
        $newUserPersonal['phone'] = '';
        $newUserPersonal['country'] = $request->get('cty');
        $newUserPersonal['country_code'] = $request->get('ctyc');
        $newUserPersonal['continent'] = $request->get('cnt');
        $newUserPersonal['continent_code'] = $request->get('cntc');
        $newUserPersonal['region'] = $request->get('r');
        $newUserPersonal['region_code'] = $request->get('rc');
        $newUserPersonal['zip'] = $request->get('z');
        $newUserPersonal['latitude'] = $request->get('la');
        $newUserPersonal['longitude'] = $request->get('lo');
        $newUserPersonal->save();

        $newMembershipMeta = New MembershipMeta;
        $newMembershipMeta['user_id'] = $newUser->id;
        $newMembershipMeta['plan'] = 'Free';
        $newMembershipMeta['storage_limit'] = '0';
        $newMembershipMeta['storage_used'] = '0';
        $newMembershipMeta['expires_at'] = \Carbon\Carbon::now()->addDays(3)->toDateTimeString();
        $newMembershipMeta->save();

        $newAnimationControl = New AnimationControl;
        $newAnimationControl['user_id'] = $newUser->id;
        $newAnimationControl['total_created_today'] = '0';
        $newAnimationControl['daily_quota'] = '0';
        $newAnimationControl->save();

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_';
        $charactersLength = strlen($characters);
        $skey = '';
        for ($i = 0; $i < 50; $i++) {
            $skey .= $characters[rand(0, $charactersLength - 1)];
        }

        $newKeyEntry = new SignupKey;
        $newKeyEntry['key'] = $skey;
        $newKeyEntry['user_id'] = $newUser->id;
        $newKeyEntry['used'] = 0;
        $newKeyEntry['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $newKeyEntry->save();

        $newUser->signupkey = $skey;

        $link = 'https://api.cssstudio.co/me/validation/signup/'.$skey;
    
        $to_name = $request->get('username');
        $to_email = $request->get('email');
        $data = array('username'=>$to_name, "link" => $link);
    
        Mail::send(['html' => 'emails.signup'], $data, function($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                    ->subject('Confirm your email and get started.');
            $message->from('no-reply@cssstudio.co','CSS Studio');
        });

        return response()->json(['message' => 'We have sent you a mail with instructions, please verify your account to get started.']);

        $token = $newUser->createToken('Laravel Personal Access Client')->accessToken;
        return response()->json(['Message' => 'Account has been created successfully. Please verify it ','token' => $token]);

    }

    public function validate_signup($key){

    $signupkey = SignupKey::where('key',$key)->firstOrFail();
    $user = User::where('id',$signupkey['user_id'])->firstOrFail();

    if($signupkey){
        if($signupkey['used'] == '0'){
            $signupkey['used'] = 1;
            $user['verified'] = 1;
            $signupkey->save();
            $user->save();

            return view('validation.signup')->withMessage($user->username."'s has been verified successfully!");
        }else{
            return view('validation.signup')->withMessage('Invalid key');
        }
    }else{
        return view('validation.signup')->withMessage('Key not found');
    }

    }
}
