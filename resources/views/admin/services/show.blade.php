@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.services.title') }}
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.services.fields.id') }}
                </th>
                <td>
                    {{ $service->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.services.fields.name') }}
                </th>
                <td>
                    {{ $service->name }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.services.fields.description') }}
                </th>
                <td>
                    {!! $service->description !!}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.services.fields.service_type') }}
                </th>
                <td>{{ $service->service_type }}</td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.services.fields.attachments') }}
                </th>
                <th>
                    @foreach($service->attachments as $attachment)
                        <a href="{{ $attachment->getUrl() }}" target="_blank">{{ $attachment->file_name }}</a>
                    @endforeach
                </th>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.services.fields.category_name') }}
                </th>
                <td>{{ $service->category->name }}</td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.services.fields.cost') }}
                </th>
                <td>{{ $service->cost }}</td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.services.fields.contact_info') }}
                </th>
                <td>{!! $service->contact_info !!}</td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.services.fields.status') }}
                </th>
                <td>{{ $service->status == 1 ? 'Active' : 'In Active' }}</td>
            </tr>
            @if($service->parent_service_id == null)
                <tr>
                    <th>
                        {{ trans('cruds.services.fields.sub_service') }}
                    </th>
                    <td>
                        @if($service->childrens->count() > 0)
                            @foreach($service->childrens as $key => $sub)
                                <a href="{{ route('admin.services.show',$sub->id) }}">{{ $sub->name }}</a><br>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @else
                <tr>
                    <th>
                        {{ trans('cruds.services.fields.parent_service_id') }}
                    </th>
                    <td>
                        <a href="{{ route('admin.services.show',$service->parent->id) }}">{{ $service->parent->name }}</a>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-primary">
            @lang('global.edit') @lang('cruds.services.title_singular')
        </a>
    </div>
@endsection
