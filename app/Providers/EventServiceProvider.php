<?php

namespace App\Providers;

use App\Events\DisputeResolutionEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Events\FundApproved;
use App\Events\TransactionFlaggedEvent;
use App\Events\ApplicationSubmittedEvent;
use App\Events\FandA\PermSecPaymentApprovedEvent;
use App\Events\PatientAppointmentEvent;
use App\Events\PaymentApprovedEvent;
use App\Events\PaymentInitiatedEvent;
use App\Events\PaymentRecommendedEvent;
use App\Listeners\FandA\PermSecPaymentApprovedNotification;
use App\Listeners\FandA\SendPaymentApprovedNotification;
use App\Listeners\FandA\SendPaymentInitiatedNotification;
use App\Listeners\FandA\SendPaymentRecommendedNotification;
use App\Listeners\SendTransactionFlagNotifications;
use App\Listeners\Patient\SendApprovalNotification;
use App\Listeners\SendDisputeResolutionNotification;
use App\Listeners\Patient\SendApplicationSubmittedNotification;
use App\Listeners\Patient\SendPatientAppointmentNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        FundApproved::class => [
            SendApprovalNotification::class,
        ],
        TransactionFlaggedEvent::class => [
            SendTransactionFlagNotifications::class,
        ],
        DisputeResolutionEvent::class => [
            SendDisputeResolutionNotification::class
        ],
        ApplicationSubmittedEvent::class => [
            SendApplicationSubmittedNotification::class
        ],
        PatientAppointmentEvent::class => [
            SendPatientAppointmentNotification::class,
        ],
        PaymentInitiatedEvent::class => [
            SendPaymentInitiatedNotification::class,
        ],
        PaymentRecommendedEvent::class => [
            SendPaymentRecommendedNotification::class,
            // SendPaymentInitiatedNotification::class,
        ],
        PaymentApprovedEvent::class => [
            SendPaymentRecommendedNotification::class,
            // SendPaymentApprovedNotification::class,
            // SendPaymentInitiatedNotification::class,
        ],
        PermSecPaymentApprovedEvent::class => [
            PermSecPaymentApprovedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
