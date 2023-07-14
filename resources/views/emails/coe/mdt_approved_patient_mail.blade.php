@component('mail::message')
# PATIENT RECOMMENDATION

This is to notify you that the patient with details below has been recommended for approval.

<p><strong>CHF ID:</strong> {{$patient->chf_id}}</p>
<p><strong>Patient Name:</strong> {{$patient->user->last_name}} {{$patient->user->first_name}}</p>
<p><strong>Primary Physician:</strong> {{$patient->primaryPhysician->last_name}} {{$patient->primaryPhysician->first_name}}</p>
<p><strong>Physician Recommended Amount:</strong> {{$patient->mdt_recommended_fund}}</p>
<p><strong>MDT Recommended Amount:</strong> {{$patient->mdt_recommended_amount}}</p>

Click on the button below to review patient's application
@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent