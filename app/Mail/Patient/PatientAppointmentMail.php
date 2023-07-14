<?php

namespace App\Mail\Patient;

use App\Models\PatientAppointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientAppointmentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $patientAppointment;

    public $firstName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PatientAppointment $patientAppointment)
    {
        //
        $this->patientAppointment=$patientAppointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user.patient.patientAppointment')->subject("Appointment Schedule");
    }
}
