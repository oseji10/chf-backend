@component('mail::message')
# PAYMENT DISBURSED NOTIFICATION

Greetings,

This is to notify you that a new payment has disbursed on the CHF.

Detail of transactions has been attached to this mail as PDF.

Kindly log into to the CHF portal using the button below to review

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Login to CHF
@endcomponent

Thanks,<br>
Cancer Health Fund (CHF)
@endcomponent