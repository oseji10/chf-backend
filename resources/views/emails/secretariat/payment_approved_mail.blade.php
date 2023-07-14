@component('mail::message')
# PAYMENT APPROVED NOTIFICATION

You are receiving this email because there is a new payment approved by DHS on the CHF Portal

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Dashboard
@endcomponent

Thanks,<br>
CHF Team
@endcomponent