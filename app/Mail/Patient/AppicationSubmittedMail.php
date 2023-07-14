<?php

namespace App\Mail\Patient;

use App\Models\ApplicationReview;
use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class AppicationSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $applicationReview;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ApplicationReview $applicationReview)
    {
        //
        $this->applicationReview = $applicationReview;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $pdf = PDF::loadView('pdfs.applicationSubmitted', ["applicationReview"=>$this->applicationReview]);
        return $this->view('emails.user.patient.applicationSubmitted')->subject("Application Submitted")/* ->attachData($pdf->output(), "application.pdf") */;
    }
}
