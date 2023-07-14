@component('mail::message')
# RECOMMENDATION REJECTED

This is to notify you that your recommendation for patient with CHF ID {{$application->patient->chf_id}} has been rejected for the reason below.

{{$reason}}

Click the button below to login and review
@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Login to CHF
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent