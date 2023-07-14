<?php

namespace App\Listeners\FandA;

use App\Mail\FandA\PaymentInitiatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentInitiatedNotification
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
        $reciepients = \App\Models\User::whereHas('roles', function ($q) {
            return $q->whereIn('role', ['CHF Admin', 'NCCP-DIR', "DHS", "PERM_SEC", "DFA"]);
        })->orWhere('id', auth()->id())->pluck('email');
        \Mail::to($reciepients)->send(new PaymentInitiatedMail($event->transactions, $event->email_template));
    }
}
