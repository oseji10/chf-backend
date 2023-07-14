<?php

namespace App\Mail\FandA;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class PermSecApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $transactions;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transactions)
    {
        //
        $this->transactions = $transactions;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        \Log::info("email sent");
        $pdf = PDF::loadView('pdfs.fanda.paymentApproved', ['transactions' => $this->transactions]);
        return $this->markdown('emails.fanda.permsec_approved_mail')->subject("PERMSEC PAYMENT APPROVAL")->attachData($pdf->output(), "transactions-" . date('Y-m-d h:i:s') . ".pdf");
        // return $this->markdown('emails.fanda.permsec_approved_mail');
    }
}
