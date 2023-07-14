@component('mail::message')
# CHF APPLICATION REVIEW
Greetings,

@if($application_review->status === 'approved')
Congratulations, the amount of NGN{{$application_review->amount_approved}} has been approved for you on the Cancer Health Fund (CHF) program.

You can now visit the primary hospital you selected during registration to start receiving care.

Your CHF unique patient ID is {{$application_review->user->patient->chf_id}}.

Please be informed that you are required to present your unique ID at the care centre.

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Login
@endcomponent
@else
We regret to inform you that your application to participate in the Cancer Health Fund (CHF) program was not successful.
@endif

Thank you,<br>
CHF Team.
@endcomponent