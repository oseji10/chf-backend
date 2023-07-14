<?php

namespace App\Events;

use App\Models\TransactionDispute;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionFlaggedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transasaction_dispute;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TransactionDispute $transasaction_dispute)
    {
        //
        $this->transaction_dispute = $transasaction_dispute;
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
