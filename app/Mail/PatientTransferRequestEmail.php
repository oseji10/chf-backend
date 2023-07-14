<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientTransferRequestEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user, $current_physician, $patient;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($patient, $current_physician, $user)
    {
        //
        $this->patient = $patient;
        $this->current_physician = $current_physician;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails/patient_transfer_request_email');
    }
}
