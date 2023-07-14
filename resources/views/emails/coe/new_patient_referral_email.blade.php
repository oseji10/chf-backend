@component('mail::message')
# CHF PATIENT REFERRAL NOTIFICATION

Dear CMD,

You are receiving this email because there is a new patient referral at your facility.

You are required to sign in to the CHF portal to approve the referral request.

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Sign in to CHF
@endcomponent

Thanks,<br>
CHF Team.
@endcomponent