<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\TypingEvent;

class MessageController extends Controller
{
    public function typingEvent(Request $request)
    {
        event(new TypingEvent($request->user_id, \Auth::user()->id, $request->isTyping));
        return [
        	'typing' => $request->isTyping
        ];
    }
}
