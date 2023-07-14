@component('mail::message')
# PAYMENT RECOMMENDED NOTIFICATION

You are receiving this email because there is a new payment recommended by DFA on the CHF Portal

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Dashboard
@endcomponent

Thanks,<br>
CHF Team
@endcomponent