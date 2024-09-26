@extends('layouts.admin')
@section('content')


    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.comment.title') }}
        </div>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.comment.fields.id') }}
                </th>
                <td>
                    {{ $comment->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.comment.fields.ticket') }}
                </th>
                <td>
                    {{ $comment->ticket->title ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.comment.fields.author_name') }}
                </th>
                <td>
                    {{ $comment->author_name }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.comment.fields.author_email') }}
                </th>
                <td>
                    {{ $comment->author_email }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.comment.fields.user') }}
                </th>
                <td>
                    {{ $comment->user->name ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.comment.fields.comment_text') }}
                </th>
                <td>
                    {!! $comment->comment_text !!}
                </td>
            </tr>
            </tbody>
        </table>
        <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
