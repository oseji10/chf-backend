<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentRecommendedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $email_template;
    public $transactions;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transactions, $email_template)
    {
        //
        $this->transactions = $transactions;
        $this->email_template = $email_template;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
