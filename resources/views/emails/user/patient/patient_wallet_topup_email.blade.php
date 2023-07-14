@component('mail::message')
# ADDITIONAL FUNDING NOTIFICATION

Dear patient,

This is to notify you that the amount of {{$wallet_topup->amount_credited}} has been credited to
your CHF wallet as additional funding. You may visit your COE to access care with the additional fund.

Thanks,<br>
CHF Team.
@endcomponent