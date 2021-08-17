<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class AnimationControl extends Model
{
    protected $table = 'animations_control';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'total_created_today', 'daily_quota', 'updated_at'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
