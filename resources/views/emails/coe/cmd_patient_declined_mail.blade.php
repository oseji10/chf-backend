@component('mail::message')
# RECOMMENDATION DECLINED

This is to notify you that your patient recommendation with detail below has been declined by the CMD.

<strong>CHF ID</strong>{{$patient->chf_id}}
<strong>PATIENT NAME</strong>{{$patient->user->last_name}} {{$patient->user->first_name}}

Click the button below to login to CHF and review
@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
CHF Portal
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
