<?php

namespace App\Providers;

use App\Providers\FundApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendApprovalNotificationToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FundApproved  $event
     * @return void
     */
    public function handle(FundApproved $event)
    {
        //
    }
}
