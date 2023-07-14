@component('mail::message')
# REFERRAL APPROVED NOTIFICATION

You are receiving this email because there is a new patient referral approved at your facility.

Kindly login to the CHF portal below to take appropriate action.

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Login to CHF
@endcomponent

Thanks,<br>
CHF Team.
@endcomponent