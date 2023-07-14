<?php

namespace App\Mail\COE;

use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MDTApprovePatientMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public Patient $patient;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Patient $patient)
    {
        //
        $this->patient = $patient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("MDT Patient Approval Notification")->markdown('emails.coe.mdt_approved_patient_mail');
    }
}
