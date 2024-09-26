@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.create') }} {{ trans('cruds.whyChooseUs.title_singular') }}</h6>
            <form action="{{ route("admin.whyChooseUs.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="title">{{ trans('cruds.whyChooseUs.fields.title') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="title" name="title" class="form-control"
                           value="{{ old('title', isset($whyChooseUs) ? $whyChooseUs->title : '') }}" required>
                    @if($errors->has('title'))
                        <em class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.whyChooseUs.fields.title_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label for="description">{{ trans('cruds.whyChooseUs.fields.description') }} <strong class="text-danger">*</strong></label>
                    <textarea id="description"style="height: 150px;" name="description" class="form-control" required>{{ old('description', isset($whyChooseUs) ? $whyChooseUs->description : '') }}</textarea>
                    @if($errors->has('description'))
                        <em class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.whyChooseUs.fields.description_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('icon') ? 'has-error' : '' }}">
                    <label for="icon">{{ trans('cruds.whyChooseUs.fields.icon') }} <strong class="text-danger">*</strong></label>
                    <input type="file" id="icon" name="icon" class="form-control"
                           value="{{ old('icon', isset($whyChooseUs) ? $whyChooseUs->icon : '') }}" required>
                    @if($errors->has('icon'))
                        <em class="invalid-feedback">
                            {{ $errors->first('icon') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.whyChooseUs.fields.icon_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.whyChooseUs.fields.status') }} <strong class="text-danger">*</strong></label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="1" {{ old('status') == '0' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '1' ? 'selected' : '' }}>In Active</option>
                    </select>
                    @if($errors->has('status'))
                        <em class="invalid-feedback">
                            {{ $errors->first('status') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.whyChooseUs.fields.status_helper') }}
                    </p>
                </div>
                <input class="btn btn-primary" type="submit" value="{{ trans('global.submit') }}">
            </form>
        </div>
    </div>

@endsection
