@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        @can('service_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route("admin.services.create") }}">
                        {{ trans('global.add') }} {{ trans('cruds.services.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.services.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Role">
                    <thead>
                    <tr>

                        <th>
                         S.NO
                        </th>
                        <th>Ref No</th>
                        <th>
                            {{ trans('cruds.services.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.services.fields.service_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.services.fields.category_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.services.fields.currency') }}
                        </th>
                        <th>
                           {{ trans('cruds.services.fields.banner') }}
                        </th>
                        <th>
                            {{ trans('cruds.services.fields.cost') }}
                        </th>
                        <th>
                            {{ trans('cruds.services.fields.status') }}
                        </th>
                        <th class="col">
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $key => $service)
                        <tr data-entry-id="{{ $service->id }}">

                            <td>
                                {{ $loop->index+1 ?? '' }}
                            </td>
                            <td>
                                {{ $service->reg_no ?? '' }}
                            </td>
                            <td>
                                {{ $service->name ?? '' }}
                            </td>
                            <td>
                                {{ $service->service_type ?? '' }}
                            </td>
                            <td>
                                {{ $service->category->name ?? '' }}
                            </td>
                            <td>
                                {{ $service->currency ?? '' }}
                            </td>
                            <td class="s_service">
                                <img src="{{ asset('images/product/' . $service->banner) }}" alt="Service Banner">
                            </td>
                            <td>
                                {{ (!empty($service->cost) ? ($service->currency == 'INR' ? '₹' : '$').$service->cost ?? '' : '-') }}
                            </td>

                            <td>
                                {{ $service->status == 1 ? 'Active' : 'In Active' }}
                            </td>
                            <td>
                                @can('service_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.services.show', $service->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('service_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.services.edit', $service->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('service_delete')
                                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST"
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
            @can('service_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.services.massDestroy') }}",
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
            $('.datatable-Role').DataTable({buttons: dtButtons})
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })

    </script>
@endsection
