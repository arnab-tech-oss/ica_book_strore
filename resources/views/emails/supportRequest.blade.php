@component('mail::layout')
@slot('header')
    @component('mail::header', ['url' => config('constant.base_url')])
    <img src="{{ config('app.setting')['company_logo'] }}" alt="App Logo">
    @endcomponent
@endslot

Hello {{ $user }},

First of all, thank you for choosing ICC Trade Desk as your <b>{{ $name }}</b>.<br>
we'll contact you later on our ICC Trade Desk platform, you’ll be in great hands!<br>

If you ever need to reach out to me, please do not hesitate to do so.<br>

Your requested details below:<br>
Support Request name => {{ $name }}<br>
Status => {{ $status }}<br>
Assigned User => {{ $assigned_user }}<br>

Thanks for your trust,<br>
ICC Trade Desk Team

@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.setting')['company_name'] }}. @lang('All rights reserved.')
    @endcomponent
@endslot
@endcomponent
