@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.edit') }} {{ trans('cruds.category.title_singular') }}</h6>
            <form action="{{ route("admin.categories.update", [$category->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.category.fields.name') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="name" name="name" class="form-control"
                           value="{{ old('name', isset($category) ? $category->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.category.fields.name_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('color') ? 'has-error' : '' }}">
                    <label for="color">{{ trans('cruds.category.fields.color') }}</label>
                    <input type="text" id="color" name="color" class="form-control colorpicker"
                           value="{{ old('color', isset($category) ? $category->color : '') }}" required>
                    @if($errors->has('color'))
                        <em class="invalid-feedback">
                            {{ $errors->first('color') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.category.fields.color_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('category_icon') ? 'has-error' : '' }}">
                    <label for="category_icon">{{ trans('cruds.category.fields.category_icon') }}</label>
                    <input type="file" id="category_icon" name="category_icon" class="form-control"
                           value="{{ old('category_icon', isset($category) ? $category->icon : '') }}" required>
                    @if($errors->has('category_icon'))
                        <em class="invalid-feedback">
                            {{ $errors->first('category_icon') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.category.fields.category_icon_helper') }}
                    </p>
                </div>
                @if(!empty($category->icon))
                    <img class="img-thumbnail" style="width: 100px;height: 100px;" src="{{ asset('img/'.$category->icon) }}">
                @endif
                <div class="my-3">
                    <input class="btn btn-primary" type="submit" value="{{ trans('global.update') }}">
                </div>
            </form>


        </div>
    </div>
@endsection

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css"
          rel="stylesheet">
@endsection

@section('scripts')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
    <script>
        $('.colorpicker').colorpicker();
    </script>
@endsection
