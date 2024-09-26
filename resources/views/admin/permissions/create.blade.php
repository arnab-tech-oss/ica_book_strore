@extends('layouts.admin')
@section('content')

<div class="col-12">
    <div class="bg-light rounded h-100 p-4">
        <h6 class="mb-4">{{ trans('global.create') }} {{ trans('cruds.permission.title_singular') }}</h6>
        <form action="{{ route("admin.permissions.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3 {{ $errors->has('title') ? 'has-error' : '' }}">
                <label for="title" class="form-label">{{ trans('cruds.permission.fields.title') }} <strong class="text-danger">*</strong></label>
                <input type="text" class="form-control" name="title" id="title" value="{{ old('title', isset($permission) ? $permission->title : '') }}" required>
                @if($errors->has('title'))
                    <em class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </em>
                @endif
            </div>
            <input class="btn btn-primary" type="submit" value="{{ trans('global.submit') }}">
        </form>
    </div>
</div>
@endsection
