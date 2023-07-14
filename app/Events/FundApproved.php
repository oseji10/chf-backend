<?php

namespace App\Events;

use App\Models\ApplicationReview;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FundApproved
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ApplicationReview $application_review;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ApplicationReview $application_review)
    {
        //
        $this->application_review = $application_review;
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
