<?php

namespace App\Listeners;

use App\Mail\Dispute\TransactionDisputeRaisedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTransactionFlagNotifications implements ShouldQueue
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        Mail::to($event->transaction_dispute->transactions[0]->user->email)->cc([
            $event->transaction_dispute->transactions[0]->biller->email,
            /* ADD SECRETARIAT, EMGE DISPUTE EMAIL AND COE ADMIN EMAIL AS COPY */
        ])->send(new TransactionDisputeRaisedMail($event->transaction_dispute));
    }
}
