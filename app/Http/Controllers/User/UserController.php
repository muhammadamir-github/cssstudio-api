<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserPersonal;
use App\Animation;
use App\AnimationControl;
use App\Element;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\UpdateKey;
use App\DeactivationKey;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\UserPayment;
use App\UserMetadata;
use App\MembershipMeta;
use App\LoginHistory;
use App\Ticket;
use App\Activity;
use App\DiscountCode;

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Sale;

class UserController extends Controller
{
    public function user(){
        $user = Auth::user();

        $response = [];
        $response['personal'] = $user->personal;
        $response['metadata'] = $user->metadata;
        $response['activity'] = $user->activity;
        $response['loginhistory'] = $user->loginhistory;

        $plan = $user->membership->plan;
        $uname = $user->username;
        $expat = $user->expires_at;
        $em = $user->email;

        $response['plan'] = $plan;
        $response['username'] = $uname;
        $response['expires_at'] = $expat;
        $response['email'] = $em;

        $user->last_login = Carbon::now();
        $user->save();
        return response()->json(['success' => $response], 200);
    }

    public function readymateanimations(){

    $user = Auth::user();
    $plan = $user->membership->plan;
    
    if($plan == 'Gold' || $plan == 'Diamond'){
        $animations = Animation::where('user_id','0')->get();
    }else{
        $animations = ['Only Gold & Diamond members can use ready made animations.'];
    }

    return response()->json(['success' => $animations], 200);

    }

    public function notifications(){
        $user = Auth::user();
        $user['notifications'] = $user->notifications;
        return response()->json(['success' => $user], 200);
    }

    public function tickets(){
        $user = Auth::user();
        $user['tickets'] = $user->tickets;
        return response()->json(['success' => $user], 200);
    }

    public function storage(){
        $user = Auth::user();
        $user['elements'] = $user->elements;
        $user['animations'] = $user->animations;

        $emptySpace = $user->membership['storage_limit'] - $user->membership['storage_used'];

        $response = [];

        $response['elements'] = $user->elements;
        $response['animations'] = $user->animations;
        $response['storage_empty'] = $emptySpace;

        $expat = $user->expires_at;
        $response['expires_at'] = $expat;
        
        return response()->json(['success' => $response], 200);
    }

    public function elements(){
        $user = Auth::user();
        $user['elements'] = $user->elements;
        return response()->json(['success' => $user], 200);
    }

    public function animations(){
        $user = Auth::user();
        $user['animations'] = $user->animations;
        return response()->json(['success' => $user], 200);
    }

    public function logins(){
        $user = Auth::user();
        $user['loginhistory'] = $user->loginhistory;
        return response()->json(['success' => $user], 200);
    }

    public function payments(){
        $user = Auth::user();

        $response = [];
        $response['payments'] = UserPayment::where('user_id',$user->id)->where('status','1')->get()->toArray();
        $response['total_spending'] = $user->metadata->total_spending;

        $expat = $user->expires_at;
        $response['expires_at'] = $expat;
        
        return response()->json(['success' => $response], 200);
    }

    public function metadata(){
        $user = Auth::user();
        $user['metadata'] = $user->metadata;
        return response()->json(['success' => $user], 200);
    }

    public function activity(){
        $user = Auth::user();
        $user['activity'] = $user->activity;
        return response()->json(['success' => $user], 200);
    }

    public function deactivate(){

    $user = Auth::user();
    $user->deactivationkey;

    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_';
    $charactersLength = strlen($characters);
    $dkey = '';
    for ($i = 0; $i < 300; $i++) {
        $dkey .= $characters[rand(0, $charactersLength - 1)];
    }

    $user->deactivationkey->delete();

    $newKeyEntry = new DeactivationKey;
    $newKeyEntry['key'] = $dkey;
    $newKeyEntry['user_id'] = $user->id;
    $newKeyEntry['used'] = 0;
    $newKeyEntry['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
    $newKeyEntry->save();

    $link = 'https://api.cssstudio.co/me/validation/deactivate/'.$dkey;

    $to_name = $user->username;
    $to_email = $user->email;
    $data = array('username'=>$to_name, "link" => $link);
    
    Mail::send(['html' => 'emails.deactivate'], $data, function($message) use ($to_name, $to_email) {
        $message->to($to_email, $to_name)
                ->subject('Confirm Account deactivation');
        $message->from('no-reply@cssstudio.co','CSS Studio');
    });
    
    return response()->json(['message' => 'Sent confirmation mail.'], 200);

    }

    public function updateprofile(Request $request){
    
    $user = Auth::user();

    foreach($user->updatekeys as $k){
        $k->delete();
    }

    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz_';
    $charactersLength = strlen($characters);
    $key = '';
    for ($i = 0; $i < 100; $i++) {
        $key .= $characters[rand(0, $charactersLength - 1)];
    }

    $newKeyEntry = new UpdateKey;
    $newKeyEntry['key'] = $key;

    if($request->has('email')){
        if($request->get('email') == $user->email){

        }else{
            $newKeyEntry['email'] = $request->get('email');
        }
    }

    if($request->has('password')){
        if($request->get('password') == $user->password){

        }else{
            $newKeyEntry['password'] = $request->get('password');
        }
    }

    if($request->has('phone')){
        if($request->get('phone') == $user->personal->phone){

        }else{
            $newKeyEntry['phone'] = $request->get('phone');
        }
    }

    if($request->has('username')){
        if($request->get('username') == $user->username){

        }else{
            $newKeyEntry['username'] = $request->get('username');
        }
    }

    $newKeyEntry['user_id'] = $user->id;
    $newKeyEntry['used'] = 0;
    $newKeyEntry['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
    $newKeyEntry->save();

    $link = 'https://api.cssstudio.co/me/validation/update/'.$key;
    
    $to_name = $user->username;
    $to_email = $user->email;
    $data = array('username'=>$to_name, "link" => $link);
    
    Mail::send(['html' => 'emails.update'], $data, function($message) use ($to_name, $to_email) {
        $message->to($to_email, $to_name)
                ->subject('Confirm your identity to update your profile.');
        $message->from('no-reply@cssstudio.co','CSS Studio');
    });
    
    return response()->json(['message' => 'Sent confirmation mail.'], 200);

    }

    public function validate_updateprofile($key){

    $updatekey = UpdateKey::where('key',$key)->firstOrFail();
    $user = User::where('id',$updatekey['user_id'])->firstOrFail();

    if($updatekey){
        if($updatekey['used'] == '0'){

            if($updatekey['email'] == null || $updatekey['email'] == ''){
                
            }else{
                $user->email = $updatekey['email'];
                $user->save();
            }

            if($updatekey['password'] == null || $updatekey['password'] == ''){
                
            }else{
                $user->password = $updatekey['password'];
                $user->save();
            }

            if($updatekey['username'] == null || $updatekey['username'] == ''){
                
            }else{
                $user->username = $updatekey['username'];
                $user->save();
            }

            if($updatekey['phone'] == null || $updatekey['phone'] == ''){
                
            }else{
                $userpersonal = UserPersonal::where('user_id',$user->id)->firstOrFail();
                if($userpersonal){
                    $userpersonal['phone'] = $updatekey['phone'];
                    $userpersonal->save();
                }
            }

            $updatekey['used'] = 1;
            $updatekey->save();

            return view('validation.update')->withMessage($user->username."'s profile has been updated successfully!");

        }else{
            return view('validation.update')->withMessage('Invalid key');
        }
    }else{
            return view('validation.update')->withMessage('Key not found');
    }

    }

    public function validate_deactivate($key){

    $deactivationkey = DeactivationKey::where('key',$key)->firstOrFail();
    $user = User::where('id',$deactivationkey['user_id'])->firstOrFail();

    if($deactivationkey){
        if($deactivationkey['used'] == '0'){

            $deactivationkey['used'] = 1;
            $deactivationkey->save();
            $user['deactivated'] = 1;
            $user->save();

            return view('validation.deactivate')->withMessage($user->username."'s account has been deactivated.");

        }else{
            return view('validation.update')->withMessage('Invalid key');
        }
    }else{
            return view('validation.update')->withMessage('Key not found');
    }

    }

    public function payment_execute($plan, $codetype, $code, Request $request){

        try{
            
        $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'AUZESmTIB3i8ufzZ-dQILm8ESVsP5xbpworfZ3aZssyNcyPjVOn4nx_4rCA3BpSvWDAD4JZFSheHklDW',     // ClientID
            'EC7HUnVJDikAAA1CpCy7mEcsa7c1fqLV0joy92G_Zu7ptQlG44nXspChAwwAKV8OAj99kCy7cX1vVwUh'      // ClientSecret
            //'AR6YsgTTCUlUNjF-CtBUm3oa0blUoLp4wIyDKn6MZ_qZ0mX5fdtrNfntZURF4NLFUnPRJSV38ZFXaZgL', // sandbox id
            //'EE97fkd-bqgAC2iHY4q0EQtjMHb-A8RR7KWkxygihzdfw3NRpTM4dJi4fOC1iiFBD4KP45r995sw-7Q_'  // sandbox secret
        )
        );

        $apiContext->setConfig(
              array(
                'mode' => 'live',
              )
        );

        $user = Auth::user();

        $paymentId = $request->get('payId');
        $payment = Payment::get($paymentId, $apiContext);

        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('payerID'));

        $transaction = new Transaction();
        $amount = new Amount();

        $details = new Details();

        $userPayment = UserPayment::where('user_id',$user->id)->where('status','2')->where('pp_payment_id','')->where('pp_sale_id','')->where('method','')->orderBy('created_at', 'desc')->firstOrFail();

        $codeValidity = '';
        $codeDiscount = '';

        if($codetype == 'referral'){
        	$referrerUser = User::where("referrer_code",$code)->first();
        	if($referrerUser !== null){
        		$codeValidity = 'Valid';
        		$codeDiscount = '25%';
        	}else{
        		if($referrerUser == null){
        			$codeValidity = 'Invalid';
        		}else{
        			if($referrerUser == $user){
        			    $codeValidity = 'Invalid';
        		    }else{
        		    	if($referrerUser['deactivated'] == '1'){
        			        $codeValidity = 'Invalid';
        		        }
        		    }
        		}
        	}
        }else{
        	if($codetype == 'discount'){
        		$discountCodes = DiscountCode::where('active','1')->get();
                foreach($discountCodes as $dcode){
                    if($dcode['code'] == $code){
                        $codeValidity = 'Valid';
                        $codeDiscount = $dcode['discount'];
                    }
                }
        	}
        }

        if($codeValidity == 'Valid' && $codeDiscount == '25%'){
        	if($plan == 'Bronze'){
                $details->setSubtotal(3.50);
                $amount->setTotal(3.50);
            }else{
                if($plan == 'Silver'){
                    $details->setSubtotal(7.50);
                    $amount->setTotal(7.50);
                }else{
                    if($plan == 'Gold'){
                        $details->setSubtotal(14.99);
                        $amount->setTotal(14.99);
                    }else{
                	    if($plan == 'Diamond'){
                             $details->setSubtotal(33.75);
                             $amount->setTotal(33.75);
                        }else{
                    	    return response()->json(['message' => 'Unidentified Plan']);
                        }
                    }
                }
            }
        }else{
            if($plan == 'Bronze'){
                $details->setSubtotal(4.99);
                $amount->setTotal(4.99);
            }else{
                if($plan == 'Silver'){
                    $details->setSubtotal(9.99);
                    $amount->setTotal(9.99);
                }else{
                    if($plan == 'Gold'){
                        $details->setSubtotal(19.99);
                        $amount->setTotal(19.99);
                    }else{
                	    if($plan == 'Diamond'){
                             $details->setSubtotal(44.99);
                             $amount->setTotal(44.99);
                        }else{
                    	    return response()->json(['message' => 'Unidentified Plan']);
                        }
                    }
                }
            }
        }

        $amount->setCurrency('USD');
        $amount->setDetails($details);

        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        $result = $payment->execute($execution, $apiContext);

        $saleId = $result->transactions[0]->related_resources[0]->sale->id;
        $saleDescription = $result->transactions[0]->description;
        $paymentId = $result->id;

        $userPayment['method'] = $result->payer->payment_method;
        $userPayment['pp_payment_id'] = $paymentId;
        $userPayment['pp_sale_id'] = $saleId;

        if($result->state == 'approved'){
            $userPayment['status'] = '1';
        }else{
            $userPayment['status'] = '0';
        }

        //return view('payment.execute')->withDescription($saleDescription)->with('saleid',$saleId)->with('paymentid',$paymentId);

        $metadata = UserMetadata::where('user_id',$user->id)->firstOrFail();

        $AnimationControl = AnimationControl::where('user_id',$user->id)->firstOrFail();

        $MembershipMeta = MembershipMeta::where('user_id',$user->id)->firstOrFail();
        $MembershipMeta['plan'] = $plan;
        $MembershipMeta['storage_used'] = '0';

        if($plan == 'Bronze'){
            $MembershipMeta['storage_limit'] = '10';
            $MembershipMeta['expires_at'] = \Carbon\Carbon::now()->addDays(30)->toDateTimeString();
            $user['expires_at'] = \Carbon\Carbon::now()->addDays(30)->toDateTimeString();
            $user['type'] = 'Premium';

            if($codeValidity == 'Valid' && $codeDiscount == '25%'){
            	$metadata['total_spending'] = $metadata['total_spending'] + 3.50;
            }else{
            	$metadata['total_spending'] = $metadata['total_spending'] + 4.99;
            }

            if($codetype == 'discount'){
                $dcode = DiscountCode::where('code',$code)->firstOrFail();
                $dcode->increment('total_uses');
                $dcode->save();
            }else{
                if($codetype == 'referral'){
                    $user['used_referrercode'] = '1';
                }
            }

        }else{
            if($plan == 'Silver'){
                $MembershipMeta['storage_limit'] = '75';
                $MembershipMeta['expires_at'] = \Carbon\Carbon::now()->addDays(30)->toDateTimeString();
                $user['expires_at'] = \Carbon\Carbon::now()->addDays(30)->toDateTimeString();
                $user['type'] = 'Premium';

                if($codeValidity == 'Valid' && $codeDiscount == '25%'){
            	    $metadata['total_spending'] = $metadata['total_spending'] + 7.50;
                }else{
            	    $metadata['total_spending'] = $metadata['total_spending'] + 9.99;
                }

                $AnimationControl['daily_quota'] = '15';

                if($codetype == 'discount'){
                    $dcode = DiscountCode::where('code',$code)->firstOrFail();
                    $dcode->increment('total_uses');
                    $dcode->save();
                }else{
                    if($codetype == 'referral'){
                        $user['used_referrercode'] = '1';
                    }
                }


            }else{
                if($plan == 'Gold'){
                    $MembershipMeta['storage_limit'] = '150';
                    $MembershipMeta['expires_at'] = \Carbon\Carbon::now()->addDays(30)->toDateTimeString();
                    $user['expires_at'] = \Carbon\Carbon::now()->addDays(30)->toDateTimeString();
                    $user['type'] = 'Premium';

                    if($codeValidity == 'Valid' && $codeDiscount == '25%'){
            	        $metadata['total_spending'] = $metadata['total_spending'] + 14.99;
                    }else{
            	        $metadata['total_spending'] = $metadata['total_spending'] + 19.99;
                    }
                    
                    $AnimationControl['daily_quota'] = '50';

                    if($codetype == 'discount'){
                        $dcode = DiscountCode::where('code',$code)->firstOrFail();
                        $dcode->increment('total_uses');
                        $dcode->save();
                    }else{
                        if($codetype == 'referral'){
                            $user['used_referrercode'] = '1';
                        }
                    }

                }else{
                    if($plan == 'Diamond'){
                         $MembershipMeta['storage_limit'] = '1000';
                         $MembershipMeta['expires_at'] = \Carbon\Carbon::now()->addDays(90)->toDateTimeString();
                         $user['expires_at'] = \Carbon\Carbon::now()->addDays(90)->toDateTimeString();
                         $user['type'] = 'Premium';

                         if($codeValidity == 'Valid' && $codeDiscount == '25%'){
            	             $metadata['total_spending'] = $metadata['total_spending'] + 33.75;
                         }else{
            	             $metadata['total_spending'] = $metadata['total_spending'] + 44.99;
                         }

                         $AnimationControl['daily_quota'] = '100';

                         if($codetype == 'discount'){
                             $dcode = DiscountCode::where('code',$code)->firstOrFail();
                             $dcode->increment('total_uses');
                             $dcode->save();
                         }else{
                             if($codetype == 'referral'){
                                 $user['used_referrercode'] = '1';
                             }
                         }

                    }else{
                        $MembershipMeta['storage_limit'] = '0';
                    }
                }
            }
        }
        
        $user->metadata->increment('total_payments');

        $userPayment->save();
        $MembershipMeta->save();
        $user->save();
        $metadata->save();
        $AnimationControl->save();

        return response()->json(['message' => 'success', 'data' => ['si' => $saleId, 'pi' => $paymentId]]);

        }catch (\PayPal\Exception\PayPalConnectionException $ex) {
            //return view('payment.executeAlreadyDone');
            return response()->json(['message' => 'already executed.']);
        }
    }

    public function payment_create(Request $request){

        $user = Auth::user();

        $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'AUZESmTIB3i8ufzZ-dQILm8ESVsP5xbpworfZ3aZssyNcyPjVOn4nx_4rCA3BpSvWDAD4JZFSheHklDW',     // ClientID
            'EC7HUnVJDikAAA1CpCy7mEcsa7c1fqLV0joy92G_Zu7ptQlG44nXspChAwwAKV8OAj99kCy7cX1vVwUh'      // ClientSecret
            //'AR6YsgTTCUlUNjF-CtBUm3oa0blUoLp4wIyDKn6MZ_qZ0mX5fdtrNfntZURF4NLFUnPRJSV38ZFXaZgL', // sandbox id
            //'EE97fkd-bqgAC2iHY4q0EQtjMHb-A8RR7KWkxygihzdfw3NRpTM4dJi4fOC1iiFBD4KP45r995sw-7Q_'  // sandbox secret
        )
        );

        $apiContext->setConfig(
          array(
            'mode' => 'live',
          )
        );


        $p = $request->get('p');
        $code = $request->get('code');
        
        $codeValidity = '';
        $codeDiscount = '';
        $codetype = '';

        if($code !== '' && $code !== ' ' && $code !== null){
            
            $discountCodes = DiscountCode::where('active','1')->get();
            foreach($discountCodes as $dcode){
                if($dcode['code'] == $code){
                    $codeValidity = 'Valid';
                    $codeDiscount = $dcode['discount'];
                    $codetype = 'discount';
                }
            }

            if($codeValidity == '' || $codeValidity !== 'Valid'){

                if($user['used_referrercode'] == '0'){

                    $referrerUser = User::where('referrer_code',$code)->first();

                    if($referrerUser == null){
                        $codeValidity = 'Invalid';
                    }

                    if($referrerUser['deactivated'] == '1'){
                        $codeValidity = 'Invalid';
                    }

                    if($referrerUser === $user){
                        $codeValidity = 'Invalid';
                    }else{
                        if($referrerUser['referrer_code'] == $code){
                            if($user['used_referrercode'] == '0'){

                                if($code == '0'){
                                    $codeValidity = 'Invalid';
                                }else{
                                    $codeValidity = 'Valid';
                                    $codeDiscount = '25%';
                                    $codetype = 'referral';
                                }

                            }else{
                                if($user['used_referrercode'] == '1'){
                                    $codeValidity = 'Invalid';
                                }
                            }
                        }else{
                            $codeValidity = 'Invalid';
                        }
                    }

                }else{
                    return response(['message' => 'You have already used a referral code.']);
                }

            }

        }

        $payer = new Payer();
        $itemList = new ItemList();
        $amount = new Amount();
        $details = new Details();
        $item1 = new Item();
        $transaction = new Transaction();

        $payer->setPaymentMethod("paypal");

        if($request->get('p') == 'Bronze'){

        $item1->setName('Bronze Plan')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(4.99);

        $itemList->setItems(array($item1));
        $details->setSubtotal(4.99);
        $amount->setCurrency("USD")
               ->setTotal(4.99)
               ->setDetails($details);

        if($codeValidity == 'Valid'){
          if($codeDiscount == '25%'){
            $item1->setPrice(3.50);
            $details->setSubtotal(3.50);
            $amount->setTotal(3.50);
          }
        }

        $transaction->setDescription("Bronze Plan For CSSStudio");

        }else{

        if($request->get('p') == 'Silver'){

        $item1->setName('Silver Plan')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(9.99);

        $itemList->setItems(array($item1));
        $details->setSubtotal(9.99);
        $amount->setCurrency("USD")
               ->setTotal(9.99)
               ->setDetails($details);

        if($codeValidity == 'Valid'){
          if($codeDiscount == '25%'){
            $item1->setPrice(7.50);
            $details->setSubtotal(7.50);
            $amount->setTotal(7.50);
          }
        }

        $transaction->setDescription("Silver Plan For CSSStudio");

        }else{

        if($request->get('p') == 'Gold'){

        $item1->setName('Gold Plan')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(19.99);

        $itemList->setItems(array($item1));
        $details->setSubtotal(19.99);
        $amount->setCurrency("USD")
               ->setTotal(19.99)
               ->setDetails($details);

        if($codeValidity == 'Valid'){
          if($codeDiscount == '25%'){
            $item1->setPrice(14.99);
            $details->setSubtotal(14.99);
            $amount->setTotal(14.99);
          }
        }

        $transaction->setDescription("Gold Plan For CSSStudio");

        }else{

        if($request->get('p') == 'Diamond'){

        $item1->setName('Diamond Plan')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(44.99);

        $itemList->setItems(array($item1));
        $details->setSubtotal(44.99);
        $amount->setCurrency("USD")
               ->setTotal(44.99)
               ->setDetails($details);

        if($codeValidity == 'Valid'){
          if($codeDiscount == '25%'){
            $item1->setPrice(33.75);
            $details->setSubtotal(33.75);
            $amount->setTotal(33.75);
          }
        }

        $transaction->setDescription("Diamond Plan For CSSStudio");

        }else{
        
            return response()->json(['message' => 'Unidentified Plan']);

        }
            
        }

        }

        }

        $transaction->setAmount($amount)
                    ->setItemList($itemList)
                    ->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setCancelUrl("https://www.cssstudio.co/profile");

        if($p == 'Bronze'){
            $redirectUrls->setReturnUrl("https://www.cssstudio.co/profile/?plan=Bronze&ct=".$codetype."&c=".$code);
        }else{
            if($p == 'Silver'){
                $redirectUrls->setReturnUrl("https://www.cssstudio.co/profile/?plan=Silver&ct=".$codetype."&c=".$code);
            }else{
                if($p == 'Gold'){
                    $redirectUrls->setReturnUrl("https://www.cssstudio.co/profile/?plan=Gold&ct=".$codetype."&c=".$code);
                }else{
                	if($p == 'Diamond'){
                        $redirectUrls->setReturnUrl("https://www.cssstudio.co/profile/?plan=Diamond&ct=".$codetype."&c=".$code);
                    }else{
                    	return response()->json(['message' => 'Unidentified Plan']);
                    }
                }
            }
        }

        $payment = new Payment();
        $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));

        $payment->create($apiContext);
        $approvalUrl = $payment->getApprovalLink();

        $newPayment = new UserPayment;
        $newPayment['user_id'] = $user->id;
        $newPayment['method'] = '';
        $newPayment['pp_payment_id'] = '';
        $newPayment['pp_sale_id'] = '';
        $newPayment['status'] = '2';
        $newPayment['product'] = $p.' Plan';
        $newPayment['code'] = 'none';
        $newPayment['amount'] = '0.00';
        $newPayment['original_amount'] = '0.00';

        // status , 2 = waiting to be paid and executed, 1 = paid and executed.

        if($codeValidity == 'Valid' && $codeDiscount == '25%'){
            $newPayment['code'] = $code;
            
            if($p == 'Bronze'){
                $newPayment['amount'] = '3.50';
                $newPayment['original_amount'] = '4.99';
            }else{
                if($p == 'Silver'){
                    $newPayment['amount'] = '7.50';
                    $newPayment['original_amount'] = '9.99';
                }else{
                    if($p == 'Gold'){
                        $newPayment['amount'] = '14.99';
                        $newPayment['original_amount'] = '19.99';
                    }else{
                        if($p == 'Diamond'){
                            $newPayment['amount'] = '33.75';
                            $newPayment['original_amount'] = '44.99';
                        }else{
                            $newPayment['amount'] = '0.00';
                            $newPayment['original_amount'] = '0.00';
                        }
                    }
                }
            }
        }else{
            if($p == 'Bronze'){
                $newPayment['amount'] = '4.99';
                $newPayment['original_amount'] = '4.99';
            }else{
                if($p == 'Silver'){
                    $newPayment['amount'] = '9.99';
                    $newPayment['original_amount'] = '9.99';
                }else{
                    if($p == 'Gold'){
                        $newPayment['amount'] = '19.99';
                        $newPayment['original_amount'] = '19.99';
                    }else{
                        if($p == 'Diamond'){
                            $newPayment['amount'] = '44.99';
                            $newPayment['original_amount'] = '44.99';
                        }else{
                            $newPayment['amount'] = '0.00';
                            $newPayment['original_amount'] = '0.00';
                        }
                    }
                }
            }
        }

        $newPayment['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $newPayment->save();

        return response()->json(['message' => $approvalUrl]);
    }

    public function sale_info(Request $request){

    try{

    $apiContext = new \PayPal\Rest\ApiContext(
        new \PayPal\Auth\OAuthTokenCredential(
            'AUZESmTIB3i8ufzZ-dQILm8ESVsP5xbpworfZ3aZssyNcyPjVOn4nx_4rCA3BpSvWDAD4JZFSheHklDW',     // ClientID
            'EC7HUnVJDikAAA1CpCy7mEcsa7c1fqLV0joy92G_Zu7ptQlG44nXspChAwwAKV8OAj99kCy7cX1vVwUh'      // ClientSecret
            //'AR6YsgTTCUlUNjF-CtBUm3oa0blUoLp4wIyDKn6MZ_qZ0mX5fdtrNfntZURF4NLFUnPRJSV38ZFXaZgL', // sandbox id
            //'EE97fkd-bqgAC2iHY4q0EQtjMHb-A8RR7KWkxygihzdfw3NRpTM4dJi4fOC1iiFBD4KP45r995sw-7Q_'  // sandbox secret
    )
    );

    $apiContext->setConfig(
      array(
        'mode' => 'live',
      )
    );

    $saleId = $request->get('id');
    $sale = Sale::get($saleId, $apiContext);

    return response(['status' => $sale->state]);

    }catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return response(['status' => 'error']);
    }

    }

    public function verify_sale(Request $request){

    $user = Auth::user();
    $saleID = $request->get('sid');
    $paymentID = $request->get('pid');

    $payment = UserPayment::where('user_id',$user->id)->where('pp_sale_id',$saleID)->where('pp_payment_id',$paymentID)->firstOrFail();

    if($payment->status == '1'){
    	return response()->json(['message' => 'verified']);
    }else{
    	return response()->json(['message' => 'not verified, error']);
    }

    }

    public function admin_users(){

    $users = User::all();
    $response = [];
    $count = '0';

    foreach($users as $u){
        $user = [];

        $user['id'] = $u->id;
        $user['name'] = $u->username;
        $user['membersince'] = $u->created_at;
        $user['lastvisit'] = $u->last_login;
        
        if($u->membership !== Null){
            $user['plan'] = $u->membership->plan;
            $user['storage'] = $u->membership->storage_used.'/'.$u->membership->storage_limit;
            $user['expiringin'] = $u->membership->expires_at;
        }else{
            $user['plan'] = 'Free';
            $user['storage'] = '0/0';
            $user['expiringin'] = $u->expires_at;
        }

        if($u->personal !== Null){
        	$user['country'] = $u->personal->country.' ('.$u->personal->continent.')';
        }else{
        	$user['country'] = '';
        }

        if($u->metadata !== Null){
        	$user['totalspending'] = $u->metadata->total_spending.'$';
        }else{
        	$user['totalspending'] = $u->metadata->total_spending.'$';
        }

        $response[] = $user;
        $count++;
    }

    return response()->json(['success' => $response]);

    }

    public function admin_stats(){

    $response = [];

    $response['users'] = [];
    $response['users']['premium'] = User::where('type','Premium')->count();
    $response['users']['free'] = User::where('type','Free')->orWhere('type', 'free')->count();

    //$response['tme'] = UserPayment::where('status','1')->sum('amount');
    $response['sales'] = [];
    $response['sales'][Carbon::now()->startOfMonth()->format('F').'_'.Carbon::now()->startOfMonth()->format('Y')] = UserPayment::where('created_at', '>=', Carbon::now()->startOfMonth())->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(12)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(12)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(12))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(11))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(11)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(11)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(11))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(10))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(10)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(10)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(10))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(9))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(9)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(9)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(9))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(8))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(8)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(8)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(8))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(7))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(7)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(7)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(7))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(6))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(6)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(6)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(6))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(5))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(5)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(5)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(5))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(4))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(4)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(4)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(4))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(3))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(3)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(3)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(3))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(2))->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth(2)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(2)->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(2))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth())->where('status','1')->sum('amount');

    $response['sales'][Carbon::now()->startOfMonth()->subMonth()->format('F').'_'.Carbon::now()->startOfMonth()->subMonth()->format('Y')] = UserPayment::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth())->where('created_at','<', Carbon::now()->startOfMonth())->where('status','1')->sum('amount');

    $response['storage'] = [];

    $response['storage']['animations'] = Animation::where('user_id','!=','0')->count();
    $response['storage']['elements'] = Element::where('user_id','!=','0')->count();

    $response['elements_detail'] = [];

    $response['elements_detail']['divs'] = Element::where('user_id','!=','0')->where('type','div')->count();
    $response['elements_detail']['buttons'] = Element::where('user_id','!=','0')->where('type','button')->count();
    $response['elements_detail']['images'] = Element::where('user_id','!=','0')->where('type','image')->count();
    $response['elements_detail']['videos'] = Element::where('user_id','!=','0')->where('type','video')->count();
    $response['elements_detail']['textareas'] = Element::where('user_id','!=','0')->where('type','textarea')->count();
    $response['elements_detail']['textinputs'] = Element::where('user_id','!=','0')->where('type','input')->count();
    $response['elements_detail']['paragraphs'] = Element::where('user_id','!=','0')->where('type','paragraph')->count();
    $response['elements_detail']['headings'] = Element::where('user_id','!=','0')->where('type','heading')->count();

    $response['planupgrades'] = [];
    $response['planupgrades']['bronze'] = UserPayment::where('status','1')->where('product','Bronze Plan')->count();
    $response['planupgrades']['gold'] = UserPayment::where('status','1')->where('product','Gold Plan')->count();
    $response['planupgrades']['silver'] = UserPayment::where('status','1')->where('product','Silver Plan')->count();
    $response['planupgrades']['diamond'] = UserPayment::where('status','1')->where('product','Diamond Plan')->count();

    $response['tickets'] = [];

    $response['tickets']['account'] = Ticket::where('category','Account')->count();
    $response['tickets']['billing'] = Ticket::where('category','Billing')->count();
    $response['tickets']['application_software'] = Ticket::where('category','Application/Software')->count();
    $response['tickets']['performance_issue'] = Ticket::where('category','Performance Issue')->count();
    $response['tickets']['suggestion'] = Ticket::where('category','Suggestion')->count();
    $response['tickets']['bug_report'] = Ticket::where('category','Bug Report')->count();

    $response['users']['new_users'] = [];

    $response['users']['new_users'][Carbon::now()->startOfMonth()->format('F').'_'.Carbon::now()->startOfMonth()->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth())->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth()->format('F').'_'.Carbon::now()->startOfMonth()->subMonth()->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth())->where('created_at','<', Carbon::now()->startOfMonth())->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(2)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(2)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(2))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth())->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(3)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(3)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(3))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(2))->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(4)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(4)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(4))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(3))->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(5)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(5)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(5))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(4))->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(6)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(6)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(6))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(5))->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(7)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(7)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(7))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(6))->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(8)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(8)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(8))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(7))->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(9)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(9)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(9))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(8))->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(10)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(10)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(10))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(9))->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(11)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(11)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(11))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(10))->count();

    $response['users']['new_users'][Carbon::now()->startOfMonth()->subMonth(12)->format('F').'_'.Carbon::now()->startOfMonth()->subMonth(12)->format('Y')] = User::where('created_at', '>', Carbon::now()->startOfMonth()->subMonth(12))->where('created_at','<', Carbon::now()->startOfMonth()->subMonth(11))->count();

    return response()->json(['success' => $response]);

    }

    public function admin_search_users_count($keyword, $field){

    if($field == 'u'){
        $users = User::where('username','like', '%' .$keyword)->count();
    }else{
        if($field == 'e'){
            $users = User::where('email','like', '%' .$keyword)->count();
        }else{
            if($field == 'ue'){
                $users = User::where('email','like', '%' .$keyword)->orWhere('username','like', '%' .$keyword)->count();
            }else{
                if($field == 'i'){
                     $users = User::where('id',$keyword)->count();
                }
            }
        }
    }

    $response = [];
    $response['keyword'] = $keyword;
    $response['count'] = $users;

    return response()->json(['success' => $response]);

    }

    public function admin_search_users($keyword, $field){

    if($field == 'u'){
        $users = User::where('username','like', '%' .$keyword)->get();
    }else{
        if($field == 'e'){
            $users = User::where('email','like', '%' .$keyword)->get();
        }else{
            if($field == 'ue'){
                $users = User::where('email','like', '%' .$keyword)->orWhere('username','like', '%' .$keyword)->get();
            }else{
                if($field == 'i'){
                    $users = User::where('id',$keyword)->get();
                }
            }
        }
    }

    $response = [];
    $response['keyword'] = $keyword;
    $response['count'] = $users->count();
    $response['users'] = [];

    $count = 0;

    foreach($users as $u){
        $user = [];

        $user['id'] = $u->id;
        $user['name'] = $u->username;
        $user['membersince'] = $u->created_at;
        $user['lastvisit'] = $u->last_login;
        
        if($u->membership !== Null){
            $user['plan'] = $u->membership->plan;
            $user['storage'] = $u->membership->storage_used.'/'.$u->membership->storage_limit;
            $user['expiringin'] = $u->membership->expires_at;
        }else{
            $user['plan'] = 'Free';
            $user['storage'] = '0/0';
            $user['expiringin'] = $u->expires_at;
        }

        if($u->personal !== Null){
            $user['country'] = $u->personal->country.' ('.$u->personal->continent.')';
        }else{
            $user['country'] = '';
        }

        if($u->metadata !== Null){
            $user['totalspending'] = $u->metadata->total_spending.'$';
        }else{
            $user['totalspending'] = $u->metadata->total_spending.'$';
        }

        $response['users'][] = $user;
        $count++;
    }

    return response()->json(['success' => $response]);

    }

    public function admin_user_general(Request $request){

    $validator = Validator::make($request->all(),[
    	'user_id' => 'required|string',
    ]);

    if($validator->fails()){
    	return response()->json(['message' => 'an error occured.']);
    }

    $user = User::where('id',$request->get('user_id'))->firstOrFail();

    $response = [];
    $response['general'] = [];
    $response['money'] = [];
    $response['membership'] = [];
    $response['items'] = [];
    $response['tickets'] = [];
    $response['location'] = [];

    $response['general']['username'] = $user->username;
    $response['general']['email'] = $user->email;
    $response['general']['last_visit'] = $user->last_login;
    $response['general']['ip_address'] = $user->ip_address;

    $response['money']['total_money_spent'] = $user->metadata->total_spending.'$';;
    $response['money']['total_payments'] = $user->payments->count();
    $response['money']['total_completed_payments'] = $user->payments()->where('status','1')->count();

    $response['membership']['membership_plan'] = $user->membership->plan;
    $response['membership']['membership_expires_at'] = $user->membership->expires_at;

    $response['items']['storage_space'] = $user->membership->storage_used . '/' . $user->membership->storage_limit;
    $response['items']['total_animations'] = $user->animations->count();
    $response['items']['total_elements'] = $user->elements->count();
    
    $response['tickets']['total_tickets_opened'] = $user->tickets->count();
    $response['tickets']['total_tickets_closed'] = $user->tickets()->where('status', 'Closed')->count();

    $response['location']['location_latitude'] = $user->personal->latitude;
    $response['location']['location_longitude'] = $user->personal->longitude;
    $response['location']['country'] = $user->personal->country;
    $response['location']['continent'] = $user->personal->continent;
    $response['location']['region'] = $user->personal->region;
    $response['location']['zip'] = $user->personal->zip;

    return response()->json(['success' => $response]);

    }

    public function admin_user_storage(Request $request){

    $validator = Validator::make($request->all(),[
    	'user_id' => 'required|string',
    ]);

    if($validator->fails()){
    	return response()->json(['message' => 'an error occured.']);
    }

    $user = User::where('id',$request->get('user_id'))->firstOrFail();

    $response = [];
    
    $response['elements'] = $user->elements;
    $response['animations'] = $user->animations;

    return response()->json(['success' => $response]);

    }

    public function admin_user_histories(Request $request){

    $validator = Validator::make($request->all(),[
        'user_id' => 'required|string',
    ]);

    if($validator->fails()){
        return response()->json(['message' => 'an error occured.']);
    }

    $user = User::where('id',$request->get('user_id'))->firstOrFail();

    $response = [];
    
    $response['activity'] = $user->activity;
    $response['logins'] = $user->loginhistory;
    $response['billing'] = UserPayment::where('user_id',$user->id)->where('status','1')->get()->toArray();

    return response()->json(['success' => $response]);

    }

    public function admin_live(){

    $response = [];

    $response['activity'] = Activity::where('created_at','>=',Carbon::now()->startOfMonth())->get();
    $response['logins'] = LoginHistory::where('created_at','>=',Carbon::now()->startOfMonth())->get();
    $response['billing'] = UserPayment::where('created_at','>=',Carbon::now()->startOfMonth())->get();
    $response['tickets'] = Ticket::where('created_at','>=',Carbon::now()->startOfMonth())->get();
    $response['signups'] = User::where('created_at','>=',Carbon::now()->startOfMonth())->get();

    return response()->json(['success' => $response]);

    }

    public function referralcode(Request $request){
        $validator = Validator::make($request->all(),[
            'code' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json(['message' => 'No code recieved.']);
        }

        $code = $request->get('code');

        if($code == '0'){
            return response()->json(['message' => 'Invalid']);
        }

        if(strlen($code) !== 8){
            return response()->json(['message' => 'Invalid']);
        }

        $discountCodes = DiscountCode::where('active','1')->get();

        foreach($discountCodes as $dcode){
            if($dcode['code'] == $request->get('code')){
                return response()->json(['message' => 'Valid']);
            }
        }

        $user = Auth::user();
        $referrerUser = User::where('referrer_code',$request->get('code'))->first();

        if($referrerUser == null){
            return response()->json(['message' => 'Invalid']);
        }

        if($referrerUser->deactivated == '1'){
            return response()->json(['message' => 'Invalid']);
        }

        if($referrerUser === $user){
            return response()->json(['message' => 'You can not use your own code.']);
        }else{
            if($referrerUser->referrer_code == $request->get('code')){
                if($user->used_referrercode == '0'){
                    return response()->json(['message' => 'Valid']);
                }else{
                    if($user->used_referrercode == '1'){
                        return response()->json(['message' => 'You have already used a referral code.']);
                    }
                }
            }else{
                return response()->json(['message' => 'Invalid']);
            }
        }

    }

}
