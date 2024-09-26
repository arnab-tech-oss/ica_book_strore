@extends('layouts.admin')
@section('content')
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">{{ trans('cruds.emailLog.title_singular') }} {{ trans('global.list') }}</h6>
        </div>
        <div class="table-responsive">
            <table
                class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Category">
                <thead>
                    <tr class="ligth">
                        <th>{{ "S.NO" }}</th>
                        <th>{{ trans('cruds.emailLog.fields.date') }}</th>
                        <th>{{ trans('cruds.emailLog.fields.receiver') }}</th>
                        <th>{{ trans('cruds.emailLog.fields.subject') }}</th>
                        <th> &nbsp;{{ trans('global.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allData as $key => $_Data)
                        <tr>
                            <td>{{ $loop->index+1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($_Data->emailDate)->format('d-M-y h:i A') }}</td>
                            <td>{{ $_Data->to }}</td>
                            <td>{{ $_Data->subject }}</td>
                            <td>
                                <button type="button" class="btn btn-primary view-details" data-toggle="modal"
                                    data-target="#openModal_{{ $key }}">
                                    View Details
                                </button>
                                <div class="modal fade bd-example-modal-lg modal-view-details eml-log-dtl-modal"
                                    id="openModal_{{ $key }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header d-flex justify-content-end">
                                                <div class="">
                                                    <button type="button" class="btn-close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="modal-body text-wrap overflow-auto w-100">
                                                <div class="d-flex flex-column">
                                                    <label>From : {{ $_Data->from ?? ' - ' }}</label>
                                                    <label>To : {{ $_Data->to ?? ' - ' }}</label>
                                                    <label>CC : {{ $_Data->cc ?? ' - ' }}</label>
                                                    <label>BCC : {{ $_Data->bcc ?? ' - ' }}</label>
                                                    <label>Time
                                                        : {!! date('d-M-y h:i A', strtotime($_Data->date)) ?? ' - ' !!}</label>
                                                </div>
                                                {!! $_Data->body !!}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            $('.datatable-Category').DataTable({
                buttons: dtButtons
            })
        })
    </script>
@endsection
