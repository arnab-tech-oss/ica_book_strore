@extends('layouts.admin')
@section('content')

    <div class="container-fluid pt-4 px-4">
        @can('cms_access')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route("admin.faq.create") }}">
                        {{ trans('global.add') }} {{ trans('cruds.faq.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.faq.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Category">
                    <thead>
                    <tr class="text-dark">

                        <th class="col">
                            {{ "S.NO" }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.faq.fields.question') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.faq.fields.answer') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.faq.fields.status') }}
                        </th>
                        <th class="col">
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($faqs as $key => $faq)
                        <tr data-entry-id="{{ $faq->id }}">
                            <td>
                                {{ $loop->index+1 ?? '' }}
                            </td>
                            <td>
                                {{ $faq->question ?? '' }}
                            </td>
                            <td>{{ $faq->answer }}</td>
                            <td>{{ $faq->status == '1' ? 'Active' : 'In Active' }}</td>
                            <td>
                                @can('cms_access')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.faq.show', $faq->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('cms_access')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.faq.edit', $faq->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('cms_access')
                                    <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST"
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                          style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger"
                                               value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            $('.datatable-Category').DataTable({buttons: dtButtons})
        })
    </script>
@endsection
