<?php

namespace App\Listeners\Patient;

use App\Events\FundApproved;
use App\Helpers\AWSHelper;
use App\Mail\Patient\FundApprovedMail;
use App\Models\ApplicationReview;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendApprovalNotification implements ShouldQueue
{
    // public ApplicationReview $application_review;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FundApproved  $event
     * @return void
     */
    public function handle(FundApproved $event)
    {
        //
        $sms = $event->application_review->status === 'approved' ? "NGN" . (string)$event->application_review->amount_approved . ' only has been approved for you on the CHF program' : "Sorry, your application for the CHF fund has been declined.";

        AWSHelper::sendSMS($event->application_review->user->phone_number, $sms);
        Mail::to($event->application_review->user->email)->send(new FundApprovedMail($event->application_review));
    }
}
