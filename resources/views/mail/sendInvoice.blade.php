@component('mail::message')
    Hello {{ $ticket['user']['name'] }},

    We hope that you are well. Please see the attached invoice {{ $invoices['id'].'/AA' }} for {{ $ticket['title'] }},
    Remember that you can contact us at any time for any questions you have.

    Thanks for your trust,
    ICC Trade Desk Team

@endcomponent
