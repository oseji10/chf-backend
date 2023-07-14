@component('mail::message')
# CMD PATIENT RECOMMENDATION

This is to notify you that there is a new patient recommendation as follows.

Patient CHF ID: {{$patient->chf_id}}
Hospital: {{$patient->coe->coe_name}}

Physician Recommended Fund: {{$patient->mdt_recommended_fund}}

MDT Recommended Fund: {{$patient->mdt_recommended_amount}}

Click the button below to review.
@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent