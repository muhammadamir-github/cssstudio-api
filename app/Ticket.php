<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\TicketReply;


class Ticket extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'support_tickets';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id','user_id', 'topic', 'category', 'status', 'created_at'];

    public function user(){
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function replies(){
        return $this->hasMany(TicketReply::class);
    }
}
