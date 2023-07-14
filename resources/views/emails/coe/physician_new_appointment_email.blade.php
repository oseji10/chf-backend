@component('mail::message')
# CHF PATIENT APPOINTMENT NOTIFICATION

Dear {{$referral->attendantStaff->last_name}} {{$referral->attendantStaff->last_name}},

You are receiving this email as notification for a new patient appointment scheduled as follows:

<strong>Patient CHF ID:</strong> {{$referral->patient->chf_id}}

<strong>Patient Name:</strong> {{$referral->patient->user->last_name}} {{$referral->patient->user->first_name}}

<strong>Appointment Date:</strong> {{$referral->appointment_date}}

@if($referral->appointment_note)
<strong>Appointment comment:</strong> {{$referral->appointment_note}}
@endif

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Sign in to CHF portal
@endcomponent

Thanks,<br>
CHF Team.
@endcomponent