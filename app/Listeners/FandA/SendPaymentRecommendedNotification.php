<?php

namespace App\Listeners\FandA;

use App\Mail\FandA\PaymentRecommendedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPaymentRecommendedNotification
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
            return $q->whereIn('role', ['NCCP-DIR', "DFA", "DHS", "PERM_SEC"])->orWhere('role', 'DHS');
        })->orWhere('id', auth()->id())->pluck('email');
        \Mail::to($reciepients)->send(new PaymentRecommendedMail($event->transactions, $event->email_template));
    }
}
