@component('mail::layout')
@slot('header')
    @component('mail::header', ['url' => config('constant.base_url')])
        <img src="{{ config('app.setting')['company_logo'] }}" alt="App Logo">
    @endcomponent
@endslot

Hello {{ $ticket['user']['name'] }},

We hope that you are well. Please see the attached invoice {{ $invoices['id'].'/AA' }} for {{ $ticket['title'] }},
Remember that you can contact us at any time for any questions you have.

Thanks for your trust,<br>
ICC Trade Desk Team

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.setting')['company_name'] }}. @lang('All rights reserved.')
    @endcomponent
@endslot
@endcomponent
