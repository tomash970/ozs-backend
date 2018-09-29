@component('mail::message')
# Hello {{$user->name}}

You changed your mail, so you need to verify this new e-mail using this button:

@component('mail::button', ['url' => route('verify', $user->verification_token)])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent