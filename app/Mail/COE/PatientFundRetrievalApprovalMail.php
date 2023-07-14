<?php

namespace App\Mail\COE;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientFundRetrievalApprovalMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $fund_retrieval;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fund_retrieval)
    {
        //
        $this->fund_retrieval = $fund_retrieval;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.coe.patient_fund_retrieval_approval');
    }
}
