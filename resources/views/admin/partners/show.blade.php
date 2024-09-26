@extends('layouts.admin')
@section('content')

    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.partners.title') }}</h6>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.partners.fields.id') }}
                </th>
                <td>
                    {{ $partner->id }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.partners.fields.company_name') }}
                </th>
                <td>
                    {{ $partner->company_name }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.partners.fields.company_logo') }}
                </th>
                <td>
                    <img class="img-thumbnail" style="width: 250px;height: 250px;" src="{{ asset('img/'.$partner->company_logo) }}" />
                </td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
