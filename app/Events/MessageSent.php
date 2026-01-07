<?php
namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageSent implements ShouldBroadcastNow {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message) {
        $this->message = $message;
    }

    public function broadcastOn() {
        return [
            new PrivateChannel("conversation.{$this->message->conversation_id}"),
        ];
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith() {
        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'sender_type' => $this->message->sender_type,
            'message' => $this->message->message,
            'conversation_id' => $this->message->conversation_id,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}
