@component('mail::layout')
@slot('header')
    @component('mail::header', ['url' => config('constant.base_url')])
        <img src="{{asset(config('app.setting')['company_logo'])}}" alt="App Logo">
    @endcomponent
@endslot

Hi {{ $user }},

Your ICC trade desk account is {{ $status }}.<br>
if you have any query then contact us on {{ $supportEmail }}.<br>

Thank you<br>
ICC Trade Desk Team

@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.setting')['company_name'] }}. @lang('All rights reserved.')
    @endcomponent
@endslot
@endcomponent
