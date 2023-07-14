@component('mail::message')
# Fund Retrieval Request

Dear user,

Please be notified that there is a new fund retrieval request to the pool as follows:

<b>COE</b>: {{$fund_retrieval->coe->coe_name}} <br />
<b>Patient Name</b>: {{$patient->last_name}} {{$patient->first_name}}<br />
<b>Patient ID</b>: {{$patient->patient->chf_id}} <br />
<b>Wallet Balance:</b>: {{$patient->wallet->balance}} <br />
<b>Managing Doctor</b>: {{$patient->patient->primaryPhysician->last_name}} {{$patient->patient->primaryPhysician->first_name}} <br /><br />

<b>Reason for retrieval: </b> {{$reason_for_retrieval}} <br /> <br />
<b>Comment: </b> <br />
{{$comment}}<br />

@component('mail::button', ['url' => 'https://chf.emgeresources.com'])
Visit CHF Portal
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent