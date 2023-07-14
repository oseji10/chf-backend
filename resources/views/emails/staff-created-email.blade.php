@component('mail::message')
# CHF ACCOUNT CREATED

Hi there!

Your Cancer health fund (CHF) account has been created. To begin usage of CHF software, kindly login. You are advised to change your default password immediately you login.

Your default password is {{$data['password']}}

This link expires in 48 hours.

@component('mail::button', ['url' => 'https://chf.emgeresources.com/login'])
Login to CHF
@endcomponent

Thanks,<br>
Cancer Health Fund (CHF)
@endcomponent
