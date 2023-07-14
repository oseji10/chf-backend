<?php

namespace App\Mail\COE;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientFundRetrievalRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $fund_retrieval;
    public $patient;
    public $reason_for_retrieval;
    public $comment;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fund_retrieval, $patient, $reason_for_retrieval, $comment)
    {
        //
        $this->fund_retrieval = $fund_retrieval;
        $this->patient = $patient;
        $this->reason_for_retrieval = $reason_for_retrieval;
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.coe.patient_fund_retrieval_request');
    }
}
