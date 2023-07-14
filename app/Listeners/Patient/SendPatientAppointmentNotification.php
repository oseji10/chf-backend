<?php

namespace App\Listeners\Patient;

use App\Events\PatientAppointmentEvent;
use App\Mail\Patient\PatientAppointmentMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPatientAppointmentNotification implements ShouldQueue
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
     * @param  PatientAppointmentEvent  $event
     * @return void
     */
    public function handle(PatientAppointmentEvent $event)
    {
        //
        Mail::to($event->patientAppointment->patient->user->email)->send(new PatientAppointmentMail($event->patientAppointment));
    }
}
