@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        @can('link_generate_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-9">
                    <a class="btn btn-success" href="{{ route("admin.links.create") }}">
                        {{ trans('global.create') }} {{ trans('cruds.links.title_singular') }} Links
                    </a>
                </div>

                <div class="col-lg-3">
                    <a class="btn btn-success" href="{{ route("payment.offline") }}">
                       Make Offline Payment
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.links.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Comment">
                    <thead>
                    <tr>
                        <th class="col">
                            {{"S.NO" }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.links.fields.ticket_id') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.links.fields.ticket_name') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.links.fields.user') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.links.fields.cost') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.links.fields.remarks') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.links.fields.link') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.links.fields.payment_status') }}
                        </th>
                        <th class="col">
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($links as $key => $link)
                        <tr data-entry-id="{{ $link->id }}">
                            <td>
                                {{ $loop->index+1 ?? '' }}
                            </td>
                            <td>
                                {{ $link->tickets->id ?? '' }}
                            </td>
                            <td>
                                {{ $link->tickets->title ?? '' }}
                            </td>
                            <td>
                                {{ $link->tickets->user->name ?? '' }}
                            </td>
                            <td>
                                {!! $link->tickets->user->currency != null ? ($link->tickets->user->currency == 'INR' ? '&#8377;' : '&#36;').$link->cost ?? '' : $link->cost ?? '' !!}
                            </td>
                            <td>
                                {{ $link->remarks ?? '' }}
                            </td>
                            <td>
                                {{ $link->payment_url }}<br>
                                <button class="btn btn-primary" onclick="copyURL('{{ $link->payment_url }}')">Click to
                                    Copy
                                </button>
                            </td>
                            <td class="text-capitalize">
                                {{ str_replace("_",' ',$link->payment_status) ?? '' }}
                            </td>
                            <td>
                                @can('link_generate_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.links.show', $link->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                {{--                                @can('link_generate_edit')--}}
                                {{--                                    <a class="btn btn-xs btn-info" href="{{ route('admin.links.edit', $link->id) }}">--}}
                                {{--                                        {{ trans('global.edit') }}--}}
                                {{--                                    </a>--}}
                                {{--                                @endcan--}}

                                @can('link_generate_delete')
                                    <form action="{{ route('admin.links.destroy', $link->id) }}" method="POST"
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
        function copyURL(val) {
            /* Copy the text inside the text field */
            const sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = val; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
            /* Alert the copied text */
            alert('Copy Text ' + val);
        }

        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('link_generate_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.links.massDestroy') }}",
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
            $('.datatable-Comment').DataTable({buttons: dtButtons})
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })

    </script>
@endsection
