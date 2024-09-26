@extends('layouts.admin')
@section('content')

    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.customer.title') }}
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.customer.fields.id') }}
                </th>
                <td>
                    {{ $user->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.customer.fields.name') }}
                </th>
                <td>
                    {{ $user->name }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.customer.fields.email') }}
                </th>
                <td>
                    {{ $user->email }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.customer.fields.country') }}
                </th>
                <td>
                    {{ isset($user->country) ?  $user->country->name  : '-'}}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.customer.fields.currency') }}
                </th>
                <td>
                    {{ $user->currency }}
                </td>
            </tr>
            <tr>
                <th>
                    Roles
                </th>
                <td>
                    @foreach($user->roles as $id => $roles)
                        <span class="label label-info label-many">{{ $roles->title }}</span>
                    @endforeach
                </td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
