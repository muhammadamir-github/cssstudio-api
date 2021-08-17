<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = ['id','code', 'active', 'name', 'created_at', 'valid_till', 'total_uses', 'discount'];
    
    public $table = 'discount_codes';
    public $timestamps = false;

}
