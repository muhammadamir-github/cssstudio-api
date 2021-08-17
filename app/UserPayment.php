<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

/**
 * @property int $id
 * @property int $user_id
 * @property int $method
 * @property int $method_transaction_id
 * @property string $product
 * @property int $amount
 * @property string $created_at
 */
class UserPayment extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'method', 'pp_payment_id', 'product', 'pp_sale_id', 'original_amount', 'amount', 'created_at', 'code'];

    protected $table = 'user_payments';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
