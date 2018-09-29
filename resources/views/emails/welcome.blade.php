@component('mail::message')
# Hello {{$user->name}}

Thank you create an account. Please verify yout email using this  button:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
