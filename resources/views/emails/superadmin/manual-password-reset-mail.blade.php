@component('mail::message')
# PASSWORD RESET NOTIFICATION

You are recieving this email because a password reset was made on your CHF account. Please note that this is a temporary system generated password and you may need to update the password to a secure and memorable password.

New password: <strong>{{$password}}</strong>

Please contact EMGE Support should you need further assistance.

@component('mail::button', ['url' => 'https://chf.emgeresources.com'])
Go to CHF portal
@endcomponent

Thanks,<br>
CHF Team
@endcomponent