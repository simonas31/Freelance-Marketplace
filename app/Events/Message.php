<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Date;

class Message implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender;
    public $chat_id;
    public $text;
    public $send_time;

    /**
     * Create a new event instance.
     */
    public function __construct($sender, $chat_id, $text)
    {
        $this->sender = $sender;
        $this->chat_id = $chat_id;
        $this->text = $text;
        $this->send_time = Date::now();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('chats.' . $this->chat_id);
    }

    public function broadcastAs()
    {
        return 'message';
    }
}
