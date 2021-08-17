<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UpdateKey extends Model
{
     /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'updatekeys';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id','key','email','phone','password','username','used','created_at','user_id'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
