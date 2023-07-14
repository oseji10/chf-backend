<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DrugBillingInvoice extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $transactions;
    public $drugs;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transactions,$drugs)
    {
        //
        $this->transactions = $transactions;
        $this->drugs=$drugs;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.drugs_billing_invoice');
    }
}
