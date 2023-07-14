<?php

namespace App\Listeners\FandA;

use App\Mail\FandA\PermSecApprovedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PermSecPaymentApprovedNotification
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
        \Log::info("Listener caught event");
        $reciepients = \App\Models\User::whereHas('roles', function ($q) {
            return $q->whereIn('role', ["DHS", "PERM_SEC", "DFA"]);
        })->orWhere('id', auth()->id())->pluck('email');
        \Mail::to($reciepients)->send(new PermSecApprovedMail($event->transactions));
    }
}
