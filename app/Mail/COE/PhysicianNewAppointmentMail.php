<?php

namespace App\Mail\COE;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PhysicianNewAppointmentMail extends Mailable/*  implements ShouldQueue */
{
    use Queueable, SerializesModels;
    public $referral;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Referral $referral)
    {
        //
        $this->referral = $referral;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.coe.physician_new_appointment_email');
    }
}
