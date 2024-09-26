@extends('layouts.admin')
@section('content')

    <div class="container-fluid pt-4 px-4">
        @can('comment_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route("admin.comments.create") }}">
                        {{ trans('global.add') }} {{ trans('cruds.comment.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.comment.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Category">
                    <thead>
                    <tr class="text-dark">

                        <th >
                            {{ 'S.NO' }}
                        </th>
                        <th >
                            {{ trans('cruds.comment.fields.ticket') }}
                        </th>
                        <th >
                            {{ trans('cruds.comment.fields.author_name') }}
                        </th>
                        <th >
                            {{ trans('cruds.comment.fields.author_email') }}
                        </th>
{{--                        <th >--}}
{{--                            {{ trans('cruds.comment.fields.user') }}--}}
{{--                        </th>--}}
                        <th >
                            {{ trans('cruds.comment.fields.comment_text') }}
                        </th>
                        <th >
                            {{ trans('cruds.comment.fields.created_at') }}
                        </th>
                        <th >
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($comments as $key => $comment)
                        <tr data-entry-id="{{ $comment->id }}">

                            <td>
                                {{ $loop->index+1  }}
                            </td>
                            <td>
                                <a target="_blank" href="{{ route('admin.tickets.show',$comment->ticket_id) }}">{{ $comment->ticket->title ?? '' }}</a>
                            </td>
                            <td>
                                {{ $comment->author_name ?? '' }}
                            </td>
                            <td>
                                {{ $comment->author_email ?? '' }}
                            </td>
{{--                            <td>--}}
{{--                                {{ $comment->user->name ?? '' }}--}}
{{--                            </td>--}}
                            <td>
                                {!! $comment->comment_text ?? '' !!}
                            </td>
                            <td>
                                {!! !empty($comment->created_at) ? \Illuminate\Support\Carbon::parse($comment->created_at)->format('d-M-Y H:i') : '' !!}
                            </td>
                            <td>
                                @can('comment_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.comments.show', $comment->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('comment_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.comments.edit', $comment->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('comment_delete')
                                    <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST"
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
            @can('comment_delete')
            let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.comments.massDestroy') }}",
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
            // dtButtons.push(dtButtons)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {

                pageLength: 50,
            });
            $('.datatable-Category').DataTable({buttons: dtButtons})
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

            // $('.datatable-Category').DataTable( {
            //     dom: 'Bfrtip',
            //     buttons: [
            //         'csv', 'excel', 'pdf', 'print'
            //     ]
            // });
        })

    </script>
@endsection
