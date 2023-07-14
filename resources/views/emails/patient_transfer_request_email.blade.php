@component('mail::message')
# PATIENT TRANSFER REQUEST EMAIL

There is a new patient transfer request with detail below

Patient ID: {{$patient->chf_id}}
Current Physician: {{$current_physician->first_name}} {{$current_physician->last_name}}
Requesting Physician: {{$user->first_name}} {{$user->last_name}}
Hospital: {{$current_physician->coe->coe_name}}

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Login to CHF
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent