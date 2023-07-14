<?php

namespace App\Mail\COE;

use App\Models\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InwardReferralFulfilledMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $referral;
    public $transaction_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Referral $referral, $transaction_id)
    {
        //
        $this->referral = $referral;
        $this->transaction_id = $transaction_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("CHF Referral Service Provided")->markdown('email.coe.inward_referral_fulfilled_email');
    }
}
