<?php

namespace App\Mail\Patient;

use App\Models\WalletTopup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientWalletTopUpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $wallet_topup;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(WalletTopup $wallet_topup)
    {
        //
        $this->wallet_topup = $wallet_topup;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.patient.patient_wallet_topup_email');
    }
}
