<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Patient;

class CMDPatientRecommendationMail extends Mailable implements ShouldQueue
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
        return $this->markdown('emails.chfadmin.cmd_patient_recommendation_mail');
    }
}
