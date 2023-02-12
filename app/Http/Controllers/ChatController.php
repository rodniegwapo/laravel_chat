<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Nette\Utils\Random;
use Illuminate\Support\Str;

class ChatController extends Controller

{

    public function index()
    {
        return User::where('email','test@example.com') ->with('message')->get();
    }

    public function messages(Request $request)
    {

        $sender = User::where('name', $request->username)->first();

        $recipient = User::where('name', $request->username)->first();
        
        if($sender){
             $this->storeMessage($sender,$recipient,$request->message);

             return event(new Message($sender,$request->message));
        }

        $sender =   User::create(['name' => Str::random(5), 'email' => 'test@gmail.com']);

        $this->storeMessage($sender,$recipient,$request->message);
        
        return event(new Message($sender,$request->message));
        // if($user){

        //     $this->storeMessage($user,$request->message);

        //     return event(new Message($user));
        // }

        // $user = User::create(
        //     ['name' => $request->username , 'email' => 'test@gmail.com']
        // );

        // $this->storeMessage($user,$request->message);

        // return event(new Message($user));
    }

    public function storeMessage($sender,$recipient,$message)
    {
        Message::create([
           'sender_id' =>  $sender->id,
           'recipient_id' =>  $recipient->id,
           'message' => $message
         ]);
    }
}
