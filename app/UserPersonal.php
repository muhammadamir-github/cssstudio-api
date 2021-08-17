<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * @property int $id
 * @property int $user_id
 * @property int $phone
 * @property string $country
 * @property string $state
 * @property string $latitude
 * @property string $longitude
 */
class UserPersonal extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_personal';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'phone', 'country', 'country_code', 'region', 'region_code', 'continent', 'continent_code', 'zip', 'latitude', 'longitude'];

    protected $hidden = ['country_code','region','region_code','continent','continent_code','zip','user_id','latitude','longitude'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
