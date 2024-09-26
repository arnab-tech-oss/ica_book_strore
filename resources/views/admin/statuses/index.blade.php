@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        @can('status_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route("admin.statuses.create") }}">
                        {{ trans('global.add') }} {{ trans('cruds.status.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.status.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Role">
                    <thead>
                    <tr>

                        <th>
                        S.NO
                        </th>
                        <th>
                            {{ trans('cruds.status.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.status.fields.color') }}
                        </th>
                        <th class="col">
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($statuses as $key => $status)
                        <tr data-entry-id="{{ $status->id }}">

                            <td>
                                {{ $loop->index+1 ?? '' }}

                            </td>
                            <td>
                                {{ $status->name ?? '' }}
                            </td>
                            <td style="background-color:{{ $status->color ?? '#FFFFFF' }}"></td>
                            <td>
                                @can('status_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.statuses.show', $status->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('status_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.statuses.edit', $status->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                {{--@can('status_delete')
                                    <form action="{{ route('admin.statuses.destroy', $status->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan--}}

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
@can('status_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.statuses.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
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
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
//   dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-Role').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection
