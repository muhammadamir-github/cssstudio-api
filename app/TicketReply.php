<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Ticket;

class TicketReply extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'support_tickets_replies';
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id','user_id', 'ticket_id', 'text', 'created_at'];

    public function ticket(){
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
