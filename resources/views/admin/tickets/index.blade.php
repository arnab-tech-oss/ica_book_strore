@extends('layouts.admin')
@section('content')
    @if (auth()->user()->isUser() &&
            isset($serviceCards) &&
            !empty($serviceCards))
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Services</h6>
                </div>
                <div class="row flex-row flex-nowrap overflow-auto">
                    @foreach ($serviceCards as $key => $service)
                        <div class="col-lg-4 col-md-6 col-xl-4">
                            <div class="bg-white text-start rounded p-4">
                                <a
                                    href="{{ route('services.show', str_replace(' ', '-', str_replace('/', '&#45;&#45;', strtolower($service->name)))) }}">
                                    <div class="text-start">
                                        <h5 class="mb-3">{{ $service->name }}</h5>
                                        <h6 class="mb-3">
                                            {{ !empty($service->cost) ? ($service->currency == 'USD' ? '$' : '₹') . $service->cost : '-' }}
                                        </h6>
                                    </div>
                                </a>
                                <a class="theme-btn btn-sm bg-primary"
                                    href="{{ route('admin.tickets.createWithService', $service->id) }}">
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    Add To Cart</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <div class="container-fluid pt-4 px-4">
        @can('ticket_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route('admin.tickets.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.ticket.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Ticket">
                    <thead>
                        <tr>
                            <th>
                                S.NO
                            </th>

                            <th>Reg No</th>

                            <th>
                                {{ trans('cruds.ticket.fields.title') }}
                            </th>
                            <th>
                                {{ trans('cruds.ticket.fields.service') }}
                            </th>
                            <th>
                                {{ trans('cruds.ticket.fields.status') }}
                            </th>
                            <th>
                                {{ trans('cruds.ticket.fields.created_by') }}
                            </th>
                            <th>
                                {{ trans('cruds.ticket.fields.assigned_to_user') }}
                            </th>
                            <th class="col">
                                &nbsp;{{ trans('global.actions') }}
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let filters = `
                <form class="d-flex justify-content-start form-inline" id="filtersForm">
                  <div class="form-group mx-sm-3 mb-2">
                    <select class="form-control" name="status">
                      <option value="">All statuses</option>
                      @foreach ($statuses as $status)
                            <option value="{{ $status->id }}"{{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                      @endforeach
            </select>
          </div>
          <div class="form-group mx-sm-3 mb-2">
            <select class="form-control" name="services">
              <option value="">All Services</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"{{ request('services') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
            </select>
          </div>
        </form>`;
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('ticket_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.tickets.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                // dtButtons.push(deleteButton)
            @endcan
            let searchParams = new URLSearchParams(window.location.search)
            var i = 1;

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                searchable: true,
                aaSorting: [],
                ajax: {
                    url: "{{ route('admin.tickets.index') }}",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },


                    {
                        data: 'reg_no',
                        name: 'reg_no',
                    },


                    {
                        data: 'title',
                        name: 'title',
                        render: function(data, type, row) {
                            return '<a href="' + row.view_link + '">' + data + ' (' + row
                                .comments_count + ')</a>';
                        }
                    },
                    {
                        data: 'service',
                        name: 'service',
                        "orderable": false,
                        "searchable": false,
                        'render': function(data, type, row) {
                            let str = '';
                            if (data.length > 0) {
                                for (let i = 0; i < data.length; i++) {
                                    if (i >= 1) {
                                        str += ',';
                                    }
                                    str += '<span>' + data[i]['name'] + '</span>';
                                }
                            }
                            return str;
                        }
                    },
                    {
                        data: 'status_name',
                        name: 'status.name',
                        render: function(data, type, row) {
                            return '<span style="color:' + row.status_color + '">' + data + '</span>';
                        }
                    },
                    {
                        data: 'created_by',
                        name: 'created_by'
                    },
                    {
                        data: 'assigned_to_user',
                        name: 'assigned_to_user'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            };
            $(".datatable-Ticket").one("preInit.dt", function() {
                $(".dataTables_filter").after(filters);
                $(".form-inline").on('change', 'select', function() {
                    $('#filtersForm').submit();
                })
            });
            $('.datatable-Ticket').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        });
    </script>
@endsection
