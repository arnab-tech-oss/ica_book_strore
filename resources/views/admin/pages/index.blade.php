@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        @can('pages_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route("admin.pages.create") }}">
                        {{ trans('global.add') }} {{ trans('cruds.pages.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.pages.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Ticket">
                    <thead>
                    <tr>

                        <th class="col">
                            {{ "S.NO" }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.pages.fields.page_name') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.pages.fields.page_title') }}
                        </th>
                        <th class="col">
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pages as $key => $page)
                        <tr data-entry-id="{{ $page->id }}">

                            <td>
                                {{ $loop->index+1 ?? '' }}
                            </td>
                            <td>
                                {{ $page->page_name ?? '' }}
                            </td>
                            <td>
                                {{ $page->title ?? '' }}
                            </td>
                            <td>
                                @can('pages_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.pages.show', $page->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('pages_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.pages.edit', $page->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('pages_delete')
                                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST"
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
            $('.datatable-Ticket').DataTable({buttons: dtButtons})
        })
    </script>
@endsection
