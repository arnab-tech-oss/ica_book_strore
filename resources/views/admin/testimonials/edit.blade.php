@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.edit') }} {{ trans('cruds.testimonials.title_singular') }}</h6>
            <form action="{{ route("admin.testimonials.update", [$testimonials->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3 {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.testimonials.fields.name') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="name" name="name" class="form-control"
                           value="{{ old('name', isset($testimonials) ? $testimonials->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.testimonials.fields.name_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('testimonial') ? 'has-error' : '' }}">
                    <label for="testimonial">{{ trans('cruds.testimonials.fields.testimonial') }} <strong class="text-danger">*</strong></label>
                    <textarea style="height: 150px;" id="testimonial" name="testimonial" class="form-control"
                              required>{{ old('testimonial', isset($testimonials) ? $testimonials->testimonial : '') }}</textarea>
                    @if($errors->has('testimonial'))
                        <em class="invalid-feedback">
                            {{ $errors->first('testimonial') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.testimonials.fields.testimonial_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('company_name') ? 'has-error' : '' }}">
                    <label for="company_name">{{ trans('cruds.testimonials.fields.company_name') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="company_name" name="company_name" class="form-control"
                           value="{{ old('company_name', isset($testimonials) ? $testimonials->company_name : '') }}"
                           required>
                    @if($errors->has('company_name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('company_name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.testimonials.fields.company_name_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('designation') ? 'has-error' : '' }}">
                    <label for="designation">{{ trans('cruds.testimonials.fields.designation') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="designation" name="designation" class="form-control"
                           value="{{ old('designation', isset($testimonials) ? $testimonials->designation : '') }}"
                           required>
                    @if($errors->has('designation'))
                        <em class="invalid-feedback">
                            {{ $errors->first('designation') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.testimonials.fields.designation_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('image') ? 'has-error' : '' }}">
                    <label for="image">{{ trans('cruds.testimonials.fields.image') }} <strong class="text-danger">*</strong></label>
                    <input type="file" id="image" name="image" class="form-control"
                           value="{{ $testimonials->image }}" @if($testimonials->image == null) required @endif>
                    <img class="img-thumbnail my-2" style="width: 200px;height: auto;"
                         src="{{ asset('images/'.$testimonials->image) }}">
                    @if($errors->has('image'))
                        <em class="invalid-feedback">
                            {{ $errors->first('image') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.testimonials.fields.image_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.testimonials.fields.status') }} <strong class="text-danger">*</strong></label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="1" @if($testimonials->status == '1') selected @endif>Active</option>
                        <option value="0" @if($testimonials->status == '0') selected @endif>In Active</option>
                    </select>
                    @if($errors->has('status'))
                        <em class="invalid-feedback">
                            {{ $errors->first('status') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.testimonials.fields.status_helper') }}
                    </p>
                </div>
                <div>
                    <input class="btn btn-primary" type="submit" value="{{ trans('global.update') }}">
                </div>
            </form>


        </div>
    </div>
@endsection

