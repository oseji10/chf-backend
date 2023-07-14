<?php

namespace App\Mail\FandA;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class PaymentInitiatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $transactions;
    protected $email_template;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transactions, $email_template)
    {
        //
        $this->transactions = $transactions;
        $this->email_template = $email_template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = PDF::loadView('pdfs.fanda.paymentInitiated', ['transactions' => $this->transactions]);
        return $this->markdown($this->email_template)->subject("CHF Payment Initiated")->attachData($pdf->output(), "transactions-" . date('Y-m-d h:i:s') . ".pdf");
    }
}
