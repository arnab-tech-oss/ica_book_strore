@component('mail::layout')
@slot('header')
    @component('mail::header', ['url' => config('constant.base_url')])
        <img src="{{asset(config('app.setting')['company_logo'])}}" alt="App Logo">
    @endcomponent
@endslot

Hello {{ $user }},

Your trade desk login password is changed successfully. you can login using the new credential <a href="https://tradedesk.indianchamber.org/login">Click Here</a>.<br>
If you experience any issues logging into your account, reach out to us on below mention emails<br>
{{ $supportEmail }}.

Your credential with new password is mention below:<br>
Username => {{ $user_email }}<br>
Password => {{ $user_password }}<br>

please, do not share your credential with any other person.<br>

Thanks,<br>
ICC Trade Desk Team

@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.setting')['company_name'] }}. @lang('All rights reserved.')
    @endcomponent
@endslot
@endcomponent
