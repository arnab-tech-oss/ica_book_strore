@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.contact.title') }}
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.contact.fields.id') }}
                </th>
                <td>
                    {{ $contact->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.contact.fields.name') }}
                </th>
                <td>
                    {{ $contact->name ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.contact.fields.email') }}
                </th>
                <td>
                    {{ $contact->email }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.contact.fields.subject') }}
                </th>
                <td>
                    {{ $contact->subject }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.contact.fields.message') }}
                </th>
                <td>
                    {{ $contact->message ?? '' }}
                </td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
