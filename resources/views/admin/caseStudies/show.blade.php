@extends('layouts.admin')
@section('content')

    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.case_studies.title') }}</h6>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.case_studies.fields.id') }}
                </th>
                <td>
                    {{ $caseStudies->id }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.case_studies.fields.title') }}
                </th>
                <td>
                    {{ $caseStudies->title }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.case_studies.fields.description') }}
                </th>
                <td>
                    {!! $caseStudies->description !!}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.case_studies.fields.banner_image') }}
                </th>
                <td>
                    <img class="img-thumbnail" style="width: 250px;height: 250px;" src="{{ asset('images/'.$caseStudies->banner_image) }}" />
                </td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
