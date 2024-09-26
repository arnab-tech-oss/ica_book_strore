@extends('layouts.admin')
@section('content')
    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.auditLog.title') }}</h6>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.auditLog.fields.id') }}
                </th>
                <td>
                    {{ $auditLog->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.auditLog.fields.description') }}
                </th>
                <td>
                    {{ $auditLog->description }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.auditLog.fields.subject_id') }}
                </th>
                <td>
                    {{ $auditLog->subject_id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.auditLog.fields.subject_type') }}
                </th>
                <td>
                    {{ $auditLog->subject_type }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.auditLog.fields.user_id') }}
                </th>
                <td>
                    {{ $auditLog->user_id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.auditLog.fields.properties') }}
                </th>
                <td class="text-break">
                    @if(gettype($auditLog->properties) == 'object')
                        @php
                            $data = json_decode($auditLog->properties,true);
                        @endphp
                        @foreach($data as $key => $value)
                            @if(is_string($key) && is_string($value))
                                <span>{{ $key }} => {{ $value }}</span><br>
                            @else
                                <span>{{ json_encode($key) }} => {{ json_encode($value) }}</span><br>
                            @endif
                        @endforeach
                    @else
                        {!! $auditLog->properties !!}
                    @endif
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.auditLog.fields.host') }}
                </th>
                <td>
                    {{ $auditLog->host }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.auditLog.fields.created_at') }}
                </th>
                <td>
                    {{ $auditLog->created_at }}
                </td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
