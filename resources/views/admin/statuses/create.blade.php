@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4"> {{ trans('global.create') }} {{ trans('cruds.status.title_singular') }}</h6>
            <form action="{{ route("admin.statuses.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.status.fields.name') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($status) ? $status->name : '') }}" required>
                    @if($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.status.fields.name_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('color') ? 'has-error' : '' }}">
                    <label for="color">{{ trans('cruds.status.fields.color') }}</label>
                    <input type="text" id="color" name="color" class="form-control colorpicker" value="{{ old('color', isset($status) ? $status->color : '') }}">
                    @if($errors->has('color'))
                        <em class="invalid-feedback">
                            {{ $errors->first('color') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.status.fields.color_helper') }}
                    </p>
                </div>
                <input class="btn btn-primary" type="submit" value="{{ trans('global.submit') }}">
            </form>
        </div>
    </div>

@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script>
    $('.colorpicker').colorpicker();
</script>
@endsection
