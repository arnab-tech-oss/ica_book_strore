@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        @can('payment_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route("admin.payment.create") }}">
                        {{ trans('global.create') }} {{ trans('cruds.payment.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.payment.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Ticket">
                    <thead>
                    <tr>
                        <th class="col">
                            {{ "S.NO" }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.payment.fields.payment_id') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.payment.fields.bill_id') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.payment.fields.ticket_name') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.payment.fields.payment_link_status') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.payment.fields.payment_method') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.payment.fields.amount') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.payment.fields.user') }}
                        </th>
                        <th class="col">
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $key => $payment)
                        <tr data-entry-id="{{ $payment->id }}">
                            <td>
                                {{ $loop->index+1 ?? '' }}
                            </td>
                            <td>
                                {{ $payment->payment_id ?? '' }}
                            </td>
                            <td>
                                {{ $payment->bill_id ?? '' }}
                            </td>
                            <td>
                                {{ $payment->bill->ticket->title ?? '' }}
                            </td>
                            <td>
                                {{ $payment->payment_link_status ?? '' }}
                            </td>
                            <td>
                                {{ $payment->method ?? '' }}
                            </td>
                            <td>
                                {!! $payment->bill->ticket->user->currency != null ? ($payment->bill->ticket->user->currency == 'INR' ? '&#8377;' : '&#36;').$payment->amount ?? '' : $payment->amount ?? '' !!}
                            </td>
                            <td>
                                {{ $payment->bill->ticket->user->name ?? '' }}
                            </td>
                            <td>
                                @can('pages_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.payment.show', $payment->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            $('.datatable-Ticket').DataTable({buttons: dtButtons})
        })
    </script>
@endsection
