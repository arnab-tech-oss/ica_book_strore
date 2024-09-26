@extends('layouts.admin')
@section('content')

    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">{{ trans('global.show') }} {{ trans('cruds.pages.title') }}</h6>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.pages.fields.id') }}
                </th>
                <td>
                    {{ $page->id }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.pages.fields.page_name') }}
                </th>
                <td>
                    {{ $page->page_name }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.pages.fields.page_title') }}
                </th>
                <td>
                    {{ $page->title }}
                </td>
            </tr>
            <tr>
                <th class="w-25">
                    {{ trans('cruds.pages.fields.page_content') }}
                </th>
                <td>
                    {!! $page->content !!}
                </td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
