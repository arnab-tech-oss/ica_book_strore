@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.links.title') }}
        </div>
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th>
                        {{ trans('cruds.links.fields.id') }}
                    </th>
                    <td>
                        {{ $links->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.links.fields.ticket_id') }}
                    </th>
                    <td>
                        {{ $links->tickets->id }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.links.fields.ticket_name') }}
                    </th>
                    <td>
                        {{ $links->tickets->title }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.links.fields.user') }}
                    </th>
                    <td>
                        {{ $links->tickets->user->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.links.fields.cost') }}
                    </th>
                    <td>
                        {{ $links->cost }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.links.fields.remarks') }}
                    </th>
                    <td>
                        {{ $links->remarks }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.links.fields.link') }}
                    </th>
                    <th>
                        {{ $links->payment_url }}
                    </th>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.links.fields.payment_status') }}
                    </th>
                    <th class="text-capitalize">
                        {{ str_replace("_",' ',$links->payment_status) ?? '' }}
                    </th>
                </tr>
                </tbody>
            </table>
        </div>
        <a class="btn btn-default my-2" href="{{ route('admin.links.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
