@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0"> {{ trans('global.show') }} {{ trans('cruds.faq.title') }}</h6>
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.faq.fields.id') }}
                </th>
                <td>
                    {{ $faqs->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.faq.fields.question') }}
                </th>
                <td>
                    {{ $faqs->question }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.faq.fields.answer') }}
                </th>
                <td>
                    {{ $faqs->answer }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.faq.fields.status') }}
                </th>
                <td>
                    {{ ($faqs->status == '1' ? 'Active' : 'In Active') ?? '' }}
                </td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
