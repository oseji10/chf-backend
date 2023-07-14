<?php

namespace App\Mail\Patient;

use App\Models\ApplicationReview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FundApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ApplicationReview $application_review;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ApplicationReview $application_review)
    {
        //
        $this->application_review = $application_review;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.fund_approved');
    }
}
