<?php

namespace App\Mail;

use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/* PLEASE RENAME THIS TO MDT APPROVED MAIL */

class SocialWorkerApprovedMail extends Mailable implements ShouldQueue
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
        return $this->subject('Social Worker Approved Mail')->markdown('emails.coe.social_worker_approved_mail');
    }
}
