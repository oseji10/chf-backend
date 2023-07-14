@component('mail::message')
# Payment Initiated Notification

You are receiving this email because there is a new payment initiated on the CHF Portal

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Dashboard
@endcomponent

Thanks,<br>
CHF Team.
@endcomponent