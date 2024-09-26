@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.create') }} {{ trans('cruds.partners.title_singular') }}</h6>
            <form action="{{ route("admin.partners.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 {{ $errors->has('company_name') ? 'has-error' : '' }}">
                    <label for="company_name">{{ trans('cruds.partners.fields.company_name') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="company_name" name="company_name" class="form-control"
                           value="{{ old('company_name', isset($testimonials) ? $testimonials->company_name : '') }}" required>
                    @if($errors->has('company_name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('company_name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.partners.fields.company_name_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('company_logo') ? 'has-error' : '' }}">
                    <label for="company_logo">{{ trans('cruds.partners.fields.company_logo') }} <strong class="text-danger">*</strong></label>
                    <input type="file" id="company_logo" name="company_logo" class="form-control"
                           value="{{ old('company_logo', isset($testimonials) ? $testimonials->company_logo : '') }}" required>
                    @if($errors->has('company_logo'))
                        <em class="invalid-feedback">
                            {{ $errors->first('company_logo') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.partners.fields.company_logo_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.partners.fields.status') }} <strong class="text-danger">*</strong></label>
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
                        {{ trans('cruds.partners.fields.status_helper') }}
                    </p>
                </div>
                <input class="btn btn-primary" type="submit" value="{{ trans('global.submit') }}">
            </form>
        </div>
    </div>

@endsection
