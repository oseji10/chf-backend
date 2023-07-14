<?php

namespace App\Mail\Dispute;

use App\Models\TransactionDispute;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DisputeResolutionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $transaction_dispute;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TransactionDispute $transaction_dispute)
    {
        //
        $this->transaction_dispute = $transaction_dispute;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.dispute.dispute-resolved-email');
    }
}
