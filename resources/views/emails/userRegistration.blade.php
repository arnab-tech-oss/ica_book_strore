@component('mail::layout')
@slot('header')
    @component('mail::header', ['url' => config('constant.base_url')])
        <img src="{{asset(config('app.setting')['company_logo'])}}" alt="App Logo">
    @endcomponent
@endslot

Hello {{ $user }},

Thank you for joining <a href="https://tradedesk.indianchamber.org/">ICC Trade desk</a>.<br>
We’d like to confirm that your account was created successfully.<br>
To access ICC Trade desk user portal <a href="https://tradedesk.indianchamber.org/login">Click Here</a>.<br>
If you experience any issues logging into your account, reach out to us on below mention emails<br>
{{ $supportEmail }}.

Your credential is mention below:<br>
Username => {{ $user_email }},<br>
Password => {{ $user_password }},<br>

please, do not share your credential with any other person.<br>

Thanks,<br>
ICC Trade Desk Team

@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.setting')['company_name'] }}. @lang('All rights reserved.')
    @endcomponent
@endslot
@endcomponent
