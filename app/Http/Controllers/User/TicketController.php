<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Ticket;
use App\TicketReply;
use App\Activity;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    public function new(Request $request){
        $validator = Validator::make($request->all(), [
           'topic' => 'required|string',
           'category' => 'required|string',
           'message' => 'required|string',
        ]);

    if($validator->fails()){
        return response()->json(['message' => 'an error occured.']);
    }

    $user = auth()->user();

    $newticket = new Ticket;
    $newticket['user_id'] = $user->id;
    $newticket['topic'] = $request->get('topic');
    $newticket['category'] = $request->get('category');
    $newticket['status'] = "Open";
    $newticket['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
    $newticket->save();

    $newticketReply = new TicketReply;
    $newticketReply['user_id'] = $user->id;
    $newticketReply['ticket_id'] = $newticket->id;
    $newticketReply['text'] = $request->get('message');
    $newticketReply['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
    $newticketReply->save();

    $newactivity = new Activity;
    $newactivity['user_id'] = $user->id;
    $newactivity['type'] = 'Opened new support ticket ('.$request->get('topic').')';
    $newactivity['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
    $newactivity->save();

    return response()->json(['message' => 'New ticket opened successfully.']);

    }

    public function reply(Request $request){

    $validator = Validator::make($request->all(), [
           'ticket_id' => 'required|string',
           'text' => 'required|string',
        ]);

    if($validator->fails()){
        return response()->json(['message' => 'an error occured.']);
    }

    $user = auth()->user();

    $ticket = Ticket::where('id',$request->get('ticket_id'))->firstOrFail();

    if($ticket){
        if($ticket->status == 'Open'){
            $newticketReply = new TicketReply;
            $newticketReply['user_id'] = $user->id;
            $newticketReply['ticket_id'] = $request->get('ticket_id');
            $newticketReply['text'] = $request->get('text');
            $newticketReply['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
            $newticketReply->save();

            return response()->json(['message' => 'Replied ticket successfully.']);
        }
    }

    }

    public function replies($ticketid){

    $user = auth()->user();

    $replies = Ticket::where('id',$ticketid)->firstOrFail()->replies;
    return response()->json(['success' => $replies, 'requester_id' => $user->id]);

    }
}
