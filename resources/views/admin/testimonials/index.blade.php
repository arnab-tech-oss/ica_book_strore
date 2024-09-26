@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        @can('cms_access')
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-success" href="{{ route("admin.testimonials.create") }}">
                        {{ trans('global.add') }} {{ trans('cruds.testimonials.title_singular') }}
                    </a>
                </div>
            </div>
        @endcan
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">{{ trans('cruds.testimonials.title_singular') }} {{ trans('global.list') }}</h6>
            </div>
            <div class="table-responsive">
                <table
                    class="table text-start align-middle table-bordered table-hover mb-0 ajaxTable datatable datatable-Ticket">
                    <thead>
                    <tr>

                        <th class="col">
                      S.NO
                        </th>
                        <th class="col">
                            {{ trans('cruds.testimonials.fields.name') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.testimonials.fields.testimonial') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.testimonials.fields.company_name') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.testimonials.fields.designation') }}
                        </th>
                        <th class="col">
                            {{ trans('cruds.testimonials.fields.status') }}
                        </th>
                        <th class="col">
                            &nbsp;{{ trans('global.actions') }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($testimonials as $key => $testimonial)
                        <tr data-entry-id="{{ $testimonial->id }}">

                            <td>
                                {{ $loop->index+1 ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->name ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->testimonial ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->company_name ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->designation ?? '' }}
                            </td>
                            <td>
                                {{ $testimonial->status == '1' ? 'Active' : 'In Active' ?? '' }}
                            </td>
                            <td>
                                @can('cms_access')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.testimonials.show', $testimonial->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('cms_access')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.testimonials.edit', $testimonial->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('cms_access')
                                        <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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
            $('.datatable-Ticket').DataTable({buttons: dtButtons})
        })
    </script>
@endsection
