<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property string $latitude
 * @property string $longitude
 * @property string $created_at
 */
class LoginHistory extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_login_history';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'ip_address', 'latitude', 'longitude', 'country', 'flag' , 'created_at'];

    protected $hidden = ['latitude','longitude'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
