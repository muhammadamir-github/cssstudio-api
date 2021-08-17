<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * @property int $id
 * @property int $user_id
 * @property int $total_animaions
 * @property int $total_elements
 * @property int $total_payments
 * @property int $total_logins
 * @property int $total_spending
 */
class UserMetadata extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_metadata';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'total_animaions', 'total_elements', 'total_payments', 'total_logins', 'total_spending'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
