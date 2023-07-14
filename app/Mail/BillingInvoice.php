<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BillingInvoice extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $transactions;
    public $discount_percentage = 25; //Change to dynamic value from settings table
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
        return $this->markdown('emails.billing_invoice');
    }
}
