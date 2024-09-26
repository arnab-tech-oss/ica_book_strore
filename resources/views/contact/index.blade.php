@extends('layouts.admin')
@section('content')

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.contact.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Category">
                    <thead>
                    <tr class="text-dark">

                        <th class="col">
                           S.No
                        </th>
                        <th class="col">
                            {{ trans('cruds.contact.fields.name') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.contact.fields.email') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.contact.fields.subject') }}
                        </th>
                        <th class="col">
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contacts as $key => $contact)
                        <tr data-entry-id="{{ $contact->id }}">

                            <td>
                                {{ $loop->index+1}}
                            </td>
                            <td>
                                {{ $contact->name ?? '' }}
                            </td>
                            <td>
                                {{ $contact->email ?? '' }}
                            </td>
                            <td>
                                {{ $contact->subject ?? '' }}
                            </td>
                            <td>
                                @can('cms_access')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('contact.show', $contact->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
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
            $('.datatable-Category').DataTable({buttons: dtButtons})
        })
    </script>
@endsection
