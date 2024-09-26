@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        @can('priority_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route("admin.priorities.create") }}">
                        {{ trans('global.add') }} {{ trans('cruds.priority.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.priority.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Permission">
                    <thead>
                    <tr>

                        <th class="col">
                           S.NO
                        </th>
                        <th class="col">
                            {{ trans('cruds.priority.fields.name') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.priority.fields.color') }}
                        </th>
                        <th class="col">
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($priorities as $key => $priority)
                        <tr data-entry-id="{{ $priority->id }}">

                            <td>
                                {{ $loop->index+1 ?? '' }}

                            </td>
                            <td>
                                {{ $priority->name ?? '' }}
                            </td>
                            <td style="background-color:{{ $priority->color ?? '#FFFFFF' }}"></td>
                            <td>
                                @can('priority_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.priorities.show', $priority->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('priority_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.priorities.edit', $priority->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('priority_delete')
                                    <form action="{{ route('admin.priorities.destroy', $priority->id) }}" method="POST"
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
            @can('priority_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.priorities.massDestroy') }}",
                className: 'btn-danger',
                action: function (e, dt, node, config) {
                    var ids = $.map(dt.rows({selected: true}).nodes(), function (entry) {
                        return $(entry).data('entry-id')
                    });

                    if (ids.length === 0) {
                        alert('{{ trans('global.datatables.zero_selected') }}')

                        return
                    }

                    if (confirm('{{ trans('global.areYouSure') }}')) {
                        $.ajax({
                            headers: {'x-csrf-token': _token},
                            method: 'POST',
                            url: config.url,
                            data: {ids: ids, _method: 'DELETE'}
                        })
                            .done(function () {
                                location.reload()
                            })
                    }
                }
            }
            // dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                order: [[1, 'desc']],
                pageLength: 100,
            });
            $('.datatable-Priority').DataTable({buttons: dtButtons})
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })

    </script>
@endsection
