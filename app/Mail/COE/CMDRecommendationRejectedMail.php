<?php

namespace App\Mail\COE;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CMDRecommendationRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $application;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($application, $reason = '')
    {
        //
        $this->application = $application;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails/coe/cmd_recommendation_rejected_mail');
    }
}
