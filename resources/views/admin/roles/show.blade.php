@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.role.title') }}
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.role.fields.id') }}
                </th>
                <td>
                    {{ $role->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.role.fields.title') }}
                </th>
                <td>
                    {{ $role->title }}
                </td>
            </tr>
            <tr>
                <th>
                    Permissions
                </th>
                <td>
                    @foreach($role->permissions as $id => $permissions)
                        <span class="label label-info label-many">{{ $permissions->title }}</span>
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
