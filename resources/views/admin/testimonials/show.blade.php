@extends('layouts.admin')
@section('content')

    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.testimonials.title') }}</h6>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.testimonials.fields.id') }}
                </th>
                <td>
                    {{ $testimonials->id }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.testimonials.fields.name') }}
                </th>
                <td>
                    {{ $testimonials->name }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.testimonials.fields.testimonial') }}
                </th>
                <td>
                    {{ $testimonials->testimonial }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.testimonials.fields.company_name') }}
                </th>
                <td>
                    {{ $testimonials->company_name }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.testimonials.fields.designation') }}
                </th>
                <td>
                    {{ $testimonials->designation }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.testimonials.fields.image') }}
                </th>
                <td>
                    <img class="img-thumbnail" style="width: 250px;height: 250px;" src="{{ asset('images/'.$testimonials->image) }}" />
                </td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
