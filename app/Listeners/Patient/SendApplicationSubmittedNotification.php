<?php

namespace App\Listeners\Patient;

use App\Events\ApplicationSubmittedEvent;
use App\Mail\Patient\AppicationSubmittedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;


class SendApplicationSubmittedNotification implements ShouldQueue
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
     * @param  ApplicationSubmittedEvent  $event
     * @return void
     */
    public function handle(ApplicationSubmittedEvent $event)
    {
        //
        Mail::to($event->applicationReview->user->email)->send(new AppicationSubmittedMail($event->applicationReview));
    }
}
