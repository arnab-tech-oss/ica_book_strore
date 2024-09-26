@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.edit') }} {{ trans('cruds.case_studies.title_singular') }}</h6>
            <form action="{{ route("admin.caseStudies.update", [$caseStudies->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3 {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="title">{{ trans('cruds.case_studies.fields.title') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="title" name="title" class="form-control"
                           value="{{ old('title', isset($caseStudies) ? $caseStudies->title : '') }}" required>
                    @if($errors->has('title'))
                        <em class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.case_studies.fields.title_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label for="description">{{ trans('cruds.case_studies.fields.description') }} <strong class="text-danger">*</strong></label>
                    <textarea id="description" style="height: 150px;" name="description" class="form-control" required>{{ old('description', isset($caseStudies) ? $caseStudies->description : '') }}</textarea>
                    @if($errors->has('description'))
                        <em class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.case_studies.fields.description_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('banner_image') ? 'has-error' : '' }}">
                    <label for="banner_image">{{ trans('cruds.case_studies.fields.banner_image') }} <strong class="text-danger">*</strong></label>
                    <input type="file" id="banner_image" name="banner_image" class="form-control"
                           value="{{ $caseStudies->banner_image }}"
                           @if($caseStudies->banner_image == null) required @endif>
                    <img class="img-thumbnail my-2" style="width: 200px;height: auto;"
                         src="{{ asset('images/'.$caseStudies->banner_image) }}">
                    @if($errors->has('banner_image'))
                        <em class="invalid-feedback">
                            {{ $errors->first('banner_image') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.case_studies.fields.banner_image_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.case_studies.fields.status') }} <strong class="text-danger">*</strong></label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="1" @if($caseStudies->status == '1') selected @endif>Active</option>
                        <option value="0" @if($caseStudies->status == '0') selected @endif>In Active</option>
                    </select>
                    @if($errors->has('status'))
                        <em class="invalid-feedback">
                            {{ $errors->first('status') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.case_studies.fields.status_helper') }}
                    </p>
                </div>
                <div>
                    <input class="btn btn-primary" type="submit" value="{{ trans('global.update') }}">
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        CKEDITOR.replace('description', {
            filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endsection
