@component('mail::message')
# ACCOUNT VERIFICATION

Hi there!.

You need to verify your email to proceed.

Your verification code is {{$data['hash']}}

@component('mail::button', ['url' => 'https://chf.emgeresources.com/auth/verify-email/'.$data["email"]])
Verify Email
@endcomponent

Thanks,<br>
Cancer Health Fund (CHF)
@endcomponent