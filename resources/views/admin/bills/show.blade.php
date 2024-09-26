@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.bills.title') }}
        </div>
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        {{ trans('cruds.bills.fields.id') }}
                    </th>
                    <td>
                        {{ $bills->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.bills.fields.ticket_name') }}
                    </th>
                    <td>
                        {{ $bills->ticket->title }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.bills.fields.bill_cost') }}
                    </th>
                    <td>
                        {{ $bills->bill_cost }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.bills.fields.remaining_cost') }}
                    </th>
                    <td>
                        {{ $bills->remaining_cost }}
                    </td>
                </tr>
                @if(isset($bills->invoices) && !empty($bills->invoices))
                    @can('bill_generate_access')
                        <tr>
                            <th>
                                {{ trans('global.downloadFile') }}
                            </th>
                            <td>
                                <a class="btn btn-xs btn-primary m-1"
                                   href="{{ config('constant.base_url').$bills->invoices->generated_bill_path.'.pdf' }}"
                                   target="_blank" download>
                                    {{ trans('global.downloadFile') }}
                                </a>
                            </td>
                        </tr>
                    @endcan
                @endif
                </tbody>
            </table>
        </div>
        <a class="btn btn-default my-2" href="{{ route('admin.bills.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
