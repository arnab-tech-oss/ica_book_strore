@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.status.title') }}
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.status.fields.id') }}
                </th>
                <td>
                    {{ $status->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.status.fields.name') }}
                </th>
                <td>
                    {{ $status->name }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.status.fields.color') }}
                </th>
                <td style="background-color:{{ $status->color ?? '#FFFFFF' }}"></td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
