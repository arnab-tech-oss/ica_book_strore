@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-AuditLog">
                    <thead>
                    <tr class="text-dark">
                        <th class="col">
                            {{ "S.NO"}}
                        </th>
                        <th class="col">
                            {{ trans('cruds.auditLog.fields.description') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.auditLog.fields.subject_id') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.auditLog.fields.subject_type') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.auditLog.fields.user_id') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.auditLog.fields.host') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.auditLog.fields.created_at') }}
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
        $(function () {
            // let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            let dtOverrideGlobals = {
                // buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.audit-logs.index') }}",
                columns: [
                    { data: 'DT_RowIndex', 'orderable': false, 'searchable': false },
                    {data: 'description', name: 'description'},
                    {data: 'subject_id', name: 'subject_id'},
                    {data: 'subject_type', name: 'subject_type'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'host', name: 'host'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'actions', name: '{{ trans('global.actions') }}'}
                ],
                order: [[1, 'desc']],
                pageLength: 100,
            };
            $('.datatable-AuditLog').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
                    
            });


        });

    </script>
@endsection
