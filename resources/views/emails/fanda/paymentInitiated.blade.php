@component('mail::message')
# PAYMENT INITIATED ATTENTION

Greetings,

This is to notify you that a new payment has been initiated for approval.

Detail of transactions has been attached to this mail as PDF.

Kindly log into to the CHF portal using the button below to review

@component('mail::button', ['url' => 'https://chf.emgeresources.com/dashboard'])
Login to CHF
@endcomponent

Thanks,<br>
Cancer Health Fund (CHF)
@endcomponent