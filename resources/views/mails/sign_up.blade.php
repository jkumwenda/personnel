@component('mail::message')
# Welcome {{$details['name']}}
<p>To complete your registration, please click the button below to verify your email address.</p>
@component('mail::button', ['url' => 'www.google.com'])
Click here to login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
