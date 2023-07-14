@component('mail::message')
# COE Patient Additional Fund Notification

This is to notify you that additional funding has been credited into a patient's wallet with detail below.

Patient CHF ID: {{$wallet_topup->user->patient->chf_id}}

Patient Name: {{$wallet_topup->user->last_name}} {{$wallet_topup->user->first_name}} {{$wallet_topup->user->other_names ?? ""}}

Hospital: {{$wallet_topup->user->patient->coe->coe_name}}

Previous Balance: {{$wallet_topup->previous_balance}}

Additional Fund: {{$wallet_topup->amount_credited}}

@component('mail::button', ['url' => 'https://chf.emgeresources.com'])
Go to portal
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent