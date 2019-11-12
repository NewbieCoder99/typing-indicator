<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TypingEvent implements ShouldBroadcast
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender, $recipient, $isTyping;

    /**
    * Create a new event instance.
    *
    * @return void
    */
    public function __construct($sender, $recipient, $isTyping)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->isTyping = $isTyping;
    }

    /**
    * Get the channels the event should broadcast on.
    *
    * @return \Illuminate\Broadcasting\Channel|array
    */
    public function broadcastOn()
    {
        return [ 'is-typing-event_'.$this->recipient.'-'.$this->sender ];
    }
}
