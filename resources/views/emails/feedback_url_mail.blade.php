@component('mail::layout')
@slot('header')
    @component('mail::header', ['url' => config('constant.base_url')])
        <img src="{{asset(config('app.setting')['company_logo'])}}" alt="App Logo">
    @endcomponent
@endslot

Hi {{ $user_name }},

Thanks for your support request and actively using <a href="{{ $base_url }}">ICC Trade Desk</a>.

I wanted to reach out personally and ask for your opinion on your support request of <b>{{ $ticket_name }}</b>.

What’s your experience like? (e.g., amazing, terrible, etc.)

We want to be better, and your feedback helps us accomplish that. If you’re willing, it only takes a minute or two.

Share your review here <a href="{{ $url }}">Give Feedback</a>

Thanks for your trust,
ICC Trade Desk Team

@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.setting')['company_name'] }}. @lang('All rights reserved.')
    @endcomponent
@endslot
@endcomponent
