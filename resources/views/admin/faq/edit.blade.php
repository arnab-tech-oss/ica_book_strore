@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.edit') }} {{ trans('cruds.faq.title_singular') }}</h6>
            <form action="{{ route("admin.faq.update", [$faqs->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group {{ $errors->has('question') ? 'has-error' : '' }}">
                    <label for="question">{{ trans('cruds.faq.fields.question') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="question" name="question" class="form-control"
                           value="{{ old('question', isset($faqs) ? $faqs->question : '') }}" required>
                    @if($errors->has('question'))
                        <em class="invalid-feedback">
                            {{ $errors->first('question') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.faq.fields.question_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('answer') ? 'has-error' : '' }}">
                    <label for="answer">{{ trans('cruds.faq.fields.answer') }}</label>
                    <input type="text" id="answer" name="answer" class="form-control"
                           value="{{ old('answer', isset($faqs) ? $faqs->answer : '') }}">
                    @if($errors->has('answer'))
                        <em class="invalid-feedback">
                            {{ $errors->first('answer') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.faq.fields.answer_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.testimonials.fields.status') }} <strong class="text-danger">*</strong></label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="1" {{ old('status') == '1' || $faqs->status == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' || $faqs->status == '0' ? 'selected' : '' }}>In Active</option>
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
