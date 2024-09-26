@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.create') }} {{ trans('cruds.pages.title_singular') }}</h6>
            <form action="{{ route("admin.pages.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 {{ $errors->has('page_name') ? 'has-error' : '' }}">
                    <label for="page_name">{{ trans('cruds.pages.fields.page_name') }} <strong class="text-danger">*</strong></label>
<!--                    <input type="text" id="page_name" name="page_name" class="form-control"
                           value="{{ old('page_name', isset($pages) ? $pages->page_name : '') }}" required>-->
                    <select id="page_name" name="page_name" class="form-select" required>
                        <option value="">Select</option>
                        <option value="home_page">Home Page</option>
                        <option value="about_page">About Page</option>
                        <option value="testimonial_page">Testimonial Page</option>
                        <option value="terms_and_condition">Terms And Condition</option>
                    </select>
                    @if($errors->has('page_name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('page_name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.pages.fields.page_name_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('page_title') ? 'has-error' : '' }}">
                    <label for="page_title">{{ trans('cruds.pages.fields.page_title') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="page_title" name="page_title" class="form-control"
                           value="{{ old('page_title', isset($pages) ? $pages->page_title : '') }}" required>
                        @if($errors->has('page_title'))
                            <em class="invalid-feedback">
                                {{ $errors->first('page_title') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.pages.fields.page_title_helper') }}
                        </p>
                </div>
                <div class="mb-3 {{ $errors->has('page_content') ? 'has-error' : '' }}">
                    <label for="page_content">{{ trans('cruds.pages.fields.page_content') }} <strong class="text-danger">*</strong></label>
                    <textarea id="page_content" name="page_content" class="form-control" required>{{ old('page_content', isset($pages) ? $pages->page_content : '') }}</textarea>
                        @if($errors->has('page_content'))
                            <em class="invalid-feedback">
                                {{ $errors->first('page_content') }}
                            </em>
                        @endif
                        <p class="helper-block">
                            {{ trans('cruds.pages.fields.page_content_helper') }}
                        </p>
                </div>
                <input class="btn btn-primary" type="submit" value="{{ trans('global.submit') }}">
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        CKEDITOR.replace( 'page_content' ,{
            filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    </script>
@endsection
