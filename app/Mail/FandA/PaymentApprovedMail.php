<?php

namespace App\Mail\FandA;

use PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = PDF::loadView('pdfs.fanda.paymentApproved', ['transactions' => $this->transactions]);
        return $this->markdown($this->email_template)->subject("CHF Payment Approved")->attachData($pdf->output(), "transactions-" . date('Y-m-d h:i:s') . ".pdf");
    }
}
