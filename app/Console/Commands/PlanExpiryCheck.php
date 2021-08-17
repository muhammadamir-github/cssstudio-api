<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\MembershipMeta;
use App\Notification;
use Carbon\Carbon;

class PlanExpiryCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expiry:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update records of user's plan expiry.";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$users = User::all();
        foreach($users as $u){

        	if($u->deactivated == '0' && $u->verified == '1'){
        		
        		$now = Carbon::now();
        		$membership = MembershipMeta::where('user_id',$u->id)->first();

        		if($membership !== NULL){
                    if($membership->plan !== 'Free'){
                        if($now >= $membership->expires_at){
                           $membership->plan = 'Free';
                           $membership->storage_limit = '0';
                           $u->type = 'Free';
                           $u->save();
                           $membership->save();

                           $notification = new Notification;
                           $notification['user_id'] = $u->id;
                           $notification['notification'] = 'Your membership has been expired , you have lost access to premium features from now. You may upgrade your account to enjoy premium features again.';
                           $notification['created_at'] = Carbon::now();
                           $notification->save();
                        }
                    }
        		}
        	}

        }
    }
}
