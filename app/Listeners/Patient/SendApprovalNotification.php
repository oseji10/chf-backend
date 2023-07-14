<?php

namespace App\Listeners\Patient;

use App\Events\FundApproved;
use App\Helpers\AWSHelper;
use App\Helpers\CHFConstants;
use App\Mail\Patient\FundApprovedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendApprovalNotification
{
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
        $sms = strtolower($event->application_review->status) === CHFConstants::$APPROVED ? "Congratulations! NGN" . (string)$event->application_review->amount_approved . ' only has been approved for you on the CHF program.' : "We regret to inform you that your application for the CHF fund has been unsuccessful. Regards. ";

        AWSHelper::sendSMS($event->application_review->user->phone_number, $sms);
        Mail::to($event->application_review->user->email)->send(new FundApprovedMail($event->application_review));
        // AWSHelper::sendSMS('08038080619', $sms);
        // Mail::to('geefive3@gmail.com')->send(new FundApprovedMail($event->application_review));
    }
}
