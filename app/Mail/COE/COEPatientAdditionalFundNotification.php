<?php

namespace App\Mail\COE;

use App\Models\WalletTopup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class COEPatientAdditionalFundNotification extends Mailable
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
        return $this->markdown('emails.coe.coe_patient_additional_fund_notification');
    }
}
