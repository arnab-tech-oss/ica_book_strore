@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.payment.title') }}
        </div>
        <table class="table table-bordered bg-white">
            <tbody>
            <tr>
                <th class="col">
                    {{ trans('cruds.payment.fields.id') }}
                </th>
                <td>
                    {{ $payment->id ?? '' }}
                </td>
            </tr>
            <tr>
                <th class="col">
                    {{ trans('cruds.payment.fields.payment_id') }}
                </th>
                <td>
                    {{ $payment->payment_id ?? '' }}
                </td>
            </tr>
            <tr>
                <th class="col">
                    {{ trans('cruds.payment.fields.bill_id') }}
                </th>
                <td>
                    {{ $payment->bill_id ?? '' }}
                </td>
            </tr>
            <tr>
                <th class="col">
                    {{ trans('cruds.payment.fields.ticket_name') }}
                </th>
                <td>
                    {{ $payment->bill->ticket->title ?? '' }}
                </td>
            </tr>
            <tr>
                <th class="col">
                    {{ trans('cruds.payment.fields.payment_link_status') }}
                </th>
                <td>
                    {{ $payment->payment_link_status ?? '' }}
                </td>
            </tr>
            <tr>
                <th class="col">
                    {{ trans('cruds.payment.fields.payment_method') }}
                </th>
                <td>
                    {{ $payment->method ?? '' }}
                </td>
            </tr>
            <tr>
                <th class="col">
                    {{ trans('cruds.payment.fields.amount') }}
                </th>
                <td>
                    {{ $payment->amount ?? '' }}
                </td>
            </tr>
            <tr>
                <th class="col">
                    {{ trans('cruds.payment.fields.user') }}
                </th>
                <td>
                    {{ $payment->bill->ticket->user->name ?? '' }}
                </td>
            </tr>
            @if(isset($payment->bill) && isset($payment->bill->invoices) && !empty($payment->bill->invoices))
                @can('bill_generate_access')
                    <tr>
                        <th class="col">
                            {{ trans('global.downloadBill') }}
                        </th>
                        <td>
                            <a class="btn btn-xs btn-primary m-1"
                               href="{{ config('constant.base_url').$payment->bill->invoices->generated_bill_path.'.pdf' }}"
                               target="_blank" download>
                                {{ trans('global.downloadBill') }}
                            </a>
                        </td>
                    </tr>
                @endcan
            @endif
            </tbody>
        </table>
        <a class="btn btn-default my-2" href="{{ route('admin.payment.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
