@component('mail::message')
# Fund Retrieval Approved

Dear user,

Please be notified that a fund retrieval from a patient's wallet has been approved as follows:

<b>COE</b>: {{$fund_retrieval->coe->coe_name}} <br />
<b>Patient Name</b>: {{$fund_retrieval->user->last_name}} {{$fund_retrieval->user->first_name}}<br />
<b>Patient ID</b>: {{$fund_retrieval->user->patient->chf_id}} <br />
<b>Amount Retrieved:</b>: {{$fund_retrieval->amount_retrieved}} <br />


@component('mail::button', ['url' => 'https://chf.emgeresources.com'])
Visit CHF Portal
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent