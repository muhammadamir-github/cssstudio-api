<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * @property int $id
 * @property int $user_id
 * @property int $number
 * @property int $sno
 * @property string $name_on_card
 * @property string $type
 * @property int $total_transactions
 * @property string $added_at
 */
class Card extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_cards';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'number', 'sno', 'name_on_card', 'type', 'total_transactions', 'added_at'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
