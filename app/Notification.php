<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Notification extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'notifications';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id','user_id', 'notification', 'created_at'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
