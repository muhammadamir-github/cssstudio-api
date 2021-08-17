<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Animation;
use App\AnimationControl;
use App\Element;
use App\Card;
use App\Activity;
use App\LoginHistory;
use App\UserMetadata;
use App\UserPayment;
use App\UserPersonal;
use App\Ticket;
use App\Notification;
use App\UpdateKey;
use App\SignupKey;
use App\DeactivationKey;
use App\MembershipMeta;
use App\UserMedia;
use App\UserMediaStorage;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    protected $fillable = ['id','email', 'username', 'type', 'created_at', 'last_login', 'deactivated', 'verified', 'expires_at','referrer_code','used_referrercode'];
    
    public $table = 'users';
    public $timestamps = false;

    protected $hidden = ['password','ip_address','deactivated','verified','referrer_code','used_referrercode'];

    public function signupkey(){
        return $this->hasOne(SignupKey::class);
    }

    public function deactivationkey(){
        return $this->hasOne(DeactivationKey::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function media(){
        return $this->hasMany(UserMedia::class);
    }

    public function mediastorage(){
        return $this->hasOne(UserMediaStorage::class);
    }

    public function updatekeys(){
        return $this->hasMany(UpdateKey::class);
    }

    public function notifications(){
        return $this->hasMany(Notification::class);
    }

    public function animations(){
    	return $this->hasMany(Animation::class);
    }

    public function animationscontrol(){
    	return $this->hasMany(AnimationControl::class);
    }

    public function elements(){
    	return $this->hasMany(Element::class);
    }

    public function cards(){
    	return $this->hasMany(Card::class);
    }

    public function activity(){
    	return $this->hasMany(Activity::class);
    }

    public function loginhistory(){
    	return $this->hasMany(LoginHistory::class);
    }

    public function metadata(){
    	return $this->hasOne(UserMetadata::class);
    }

    public function membership(){
        return $this->hasOne(MembershipMeta::class);
    }

    public function payments(){
    	return $this->hasMany(UserPayment::class);
    }

    public function personal(){
    	return $this->hasOne(UserPersonal::class);
    }

}
