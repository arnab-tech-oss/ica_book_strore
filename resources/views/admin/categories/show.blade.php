@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0"> {{ trans('global.show') }} {{ trans('cruds.category.title') }}</h6>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.category.fields.id') }}
                </th>
                <td>
                    {{ $category->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.category.fields.name') }}
                </th>
                <td>
                    {{ $category->name }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.category.fields.color') }}
                </th>
                <td style="background-color:{{ $category->color ?? '#FFFFFF' }}"></td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
