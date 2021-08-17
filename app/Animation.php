<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $css
 * @property string $created_at
 */
class Animation extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_animations';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'css', 'created_at'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
