@component('mail::message')
# CHF REFERRAL APPOINTMENT

Dear {{$referral->patient->user->last_name}} {{$referral->patient->user->first_name}},

You are receiving this email because a new appointment has been scheduled for you are follows:

Hospital: {{$referral->referenceCOE->coe_name}}

Date: {{$referral->appointment_date}}

Assigned Staff: {{$referral->attendantStaff->last_name}} {{$referral->attendantStaff->first_name}}

Please ensure to confirm your appointment with the hospital.

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Login to CHF
@endcomponent

Thanks,<br>
CHF Team.
@endcomponent