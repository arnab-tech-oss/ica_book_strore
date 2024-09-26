@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        @can('bill_generate_create')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route('admin.bills.create') }}">
                        {{ trans('global.create') }} {{ trans('cruds.bills.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.bills.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Comment">
                    <thead>
                        <tr class="text-dark">

                            <th class="col">
                                S.NO
                            </th>

                            <th class="col">
                                Ref No
                            </th>
                            <th class="col">
                                SR Name
                            </th>
                            <th class="col">
                                {{ trans('cruds.bills.fields.bill_cost') }}
                            </th>
                            <th class="col">
                                {{ trans('cruds.bills.fields.remaining_cost') }}
                            </th>
                            <th class="col">
                                {{ trans('cruds.bills.fields.status') }}
                            </th>
                            <th class="col">
                                Action
                            </th>
                            <th class="col">
                                &nbsp;{{ trans('global.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bills as $key => $bill)
                            <tr data-entry-id="{{ $bill->id }}">

                                <td>
                                    {{ $loop->index + 1 ?? '' }}
                                </td>
                                <td>
                                    {{ $bill->ref_no ?? '' }}
                                </td>
                                <td>
                                    {{ $bill->ticket->title ?? '' }}
                                </td>
                                <td>
                                    {!! $bill->ticket->user->currency != null
                                        ? ($bill->ticket->user->currency == 'INR' ? '&#8377;' : '&#36;') . $bill->bill_cost ?? ''
                                        : $bill->bill_cost ?? '' !!}
                                </td>
                                <td>
                                    {!! $bill->ticket->user->currency != null
                                        ? ($bill->ticket->user->currency == 'INR' ? '&#8377;' : '&#36;') . $bill->remaining_cost ?? ''
                                        : $bill->remaining_cost ?? '' !!}
                                </td>
                                <td class="text-capitalize">
                                    {{ str_replace('_', ' ', $bill->status) ?? '' }}
                                </td>
                                <td>
                                    @if (isset($bill->invoices) && !empty($bill->invoices))
                                        @can('bill_generate_access')
                                   
                                            <a class="btn btn-xs btn-primary m-1"  
                                            href="{{ config('constant.base_url') . $bill->invoices->generated_bill_path . '.pdf' }}" 
                                                target="_blank" download>
                                                {{ trans('global.downloadFile') }}
                                            </a>
                                        @endcan
                                    @endif       

                                    @if (isset($bill->invoices) && !empty($bill->invoices) && !\Auth::user()->isUser())
                                        @can('bill_generate_create')
                                            <a class="btn btn-xs btn-primary m-1"
                                                href="{{ route('admin.bills.resentInvoice', $bill->id) }}">
                                                Resent Invoice
                                            </a>
                                        @endcan
                                    @endif

                                    @if (isset($bill->invoices) && !empty($bill->invoices) && !\Auth::user()->isUser())
                                        @can('bill_generate_create')
                                            <a class="btn btn-xs btn-info m-1"
                                                href="{{ route('payment.offline.id', $bill->id) }}">
                                                Pay Offline
                                            </a>
                                        @endcan
                                    @endif

                                </td>
                                <td>
                                    @can('bill_generate_show')
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.bills.show', $bill->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan

                                    @can('bill_generate_delete')
                                        <form action="{{ route('admin.bills.destroy', $bill->id) }}" method="POST"
                                            onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger"
                                                value="{{ trans('global.delete') }}">
                                        </form>
                                        <!-- <a class="btn btn-xs btn-info" href="{{ route('admin.bills.edit', $bill->id) }}">
                                                {{ trans('global.edit') }}</a> -->
                                    @endcan

                                     @if ($bill->links->count() == 0 && $bill->remaining_cost != 0 && $bill->bill_cost >= $bill->remaining_cost) 
                                         @can('bill_generate_edit') 
                                             <a class="btn btn-xs btn-info" href="{{ route('admin.bills.edit', $bill->id) }}">
                                                {{ trans('global.edit') }}
                                            </a> 
                                         @endcan 
                                     @endif 

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

        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('bill_generate_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.bills.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
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

            $.extend(true, $.fn.dataTable.defaults, {

                pageLength: 50,
            });
            $('.datatable-Comment').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })
    </script>
@endsection
