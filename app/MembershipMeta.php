<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MembershipMeta extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'membership_meta';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'plan', 'storage_limit', 'storage_used', 'expires_at'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
