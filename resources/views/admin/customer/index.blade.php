@extends('layouts.admin')
@section('content')

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.customer.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-User">
                    <thead>
                    <tr>
                        <th>
                           S.NO
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.email') }}
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.customer.fields.roles') }}
                        </th>
                        <th>
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td>
                                {{ $loop->index+1 ?? '' }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->email ?? '' }}
                            </td>
                            <td>
                                {{ $user->status == 1 ? 'Active' : 'In Active' }}
                            </td>
                            <td>
                                @foreach($user->roles as $key => $item)
                                    <span class="badge badge-info text-danger">{{ $item->title }}</span>
                                @endforeach
                            </td>
                            <td>
                                <!-- @can('user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.customer.customerShow', $user->id) }}">
                                    {{ trans('global.view') }}
                                    </a>
                                @endcan -->
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.customer.customerShow', $user->id) }}">
                                    {{ trans('global.view') }}
                                </a>

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
            $('.datatable-User').DataTable({buttons: dtButtons})
        })
    </script>
@endsection
