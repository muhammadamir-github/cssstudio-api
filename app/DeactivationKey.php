<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class DeactivationKey extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'deactivationkeys';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id','user_id', 'key', 'used', 'created_at'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
