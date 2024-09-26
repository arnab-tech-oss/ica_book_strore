@extends('layouts.admin')
@section('styles')
    <!--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
@endsection
@section('content')
    <div class="bg-light rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            {{ trans('global.show') }} {{ trans('cruds.ticket.title') }}
        </div>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.id') }}
                </th>
                <td>
                    {{ $ticket->id }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.created_at') }}
                </th>
                <td>
                    {{ $ticket->created_at }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.title') }}
                </th>
                <td>
                    {{ $ticket->title }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.content') }}
                </th>
                <td>
                    {!! $ticket->content !!}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.attachments_view') }}
                </th>
                <td>
                    @foreach($ticket->attachments as $attachment)
                        <a href="{{ asset($attachment->getUrl()) }}" target="_blank">{{ $attachment->file_name }}</a>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.status') }}
                </th>
                <td>
                    {{ $ticket->status->name ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.service') }}
                </th>
                <td>
                    {{ $ticket->ticketServices()->pluck('name') ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.created_by') }}
                </th>
                <td>
                    {{ $ticket->createdBy->name ?? '' }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.assigned_to_user') }}
                </th>
                <td>
                    {{ $ticket->assigned_to_user->name ?? ' - ' }}
                </td>
            </tr>
            <tr>
                <th>
                    {{ trans('cruds.ticket.fields.comments') }}
                </th>
                <td>
                    @if($ticket->comments == null && $ticket->documents == null)
                        <div class="row">
                            <div class="col">
                                <p>There are no comments.</p>
                            </div>
                        </div>
                        <hr/>
                    @else
                        @php
                            $commentsWithDocument = collect(array_merge($ticket->comments->toArray(),$ticket->documents->toArray()));
                            $commentsWithDocument = $commentsWithDocument->sortByDesc('created_at')->take(3)->reverse();
                        @endphp
                        <div class="text-center PreviousCommentDiv">
                            <span class="text-primary btnPreviousComment"
                                  style="cursor: pointer;">View Previous Comment</span>
                            <span class="text-primary btnHideComments"
                                  style="cursor: pointer;display: none;">Hide Comments</span>
                        </div>
                        <div class="CommentsDiv">
                            @foreach($commentsWithDocument as $comment)
                                <div class="row">
                                    <div class="col">
                                        @if(isset($comment['author_email']) && isset($comment['author_name']))
                                            <p class="font-weight-bold"><a
                                                    href="mailto:{{ $comment['author_email'] }}">{{ $comment['author_name'] }}</a>
                                                ({{ \Carbon\Carbon::parse($comment['created_at'])->format('d-m-Y H:i:s') }}
                                                )</p>
                                            <p>{!! $comment['comment_text'] !!}</p>
                                        @else
                                            <p class="font-weight-bold"><a
                                                    href="mailto:{{ \Illuminate\Support\Facades\Auth::user()->email }}">{{ \Illuminate\Support\Facades\Auth::user()->name }}</a>
                                                ({{ \Carbon\Carbon::parse($comment['created_at'])->format('d-m-Y H:i:s') }}
                                                )</p>
                                            <p class="font-weight-bold"><a
                                                    href="{{ config('constant.base_url').'invoices/'.$comment['id'].'/'.$comment['file_name'] }}"
                                                    target="_blank" download>{{ $comment['name'] }}</a></p>
                                        @endif
                                    </div>
                                </div>
                                <hr/>
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('admin.tickets.storeComment', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="comment_text">Leave a comment</label>
                            <textarea class="form-control" id="comment_text" name="comment_text" rows="3"
                                      required></textarea>
                        </div>
                        <div class="form-group {{ $errors->has('attachments') ? 'has-error' : '' }}">
                            <label for="attachments">{{ trans('cruds.ticket.fields.attachments') }} ( <span class="text-danger">zip and pdf only</span> )</label>
                            <div
                                class="needsclick dropzone border rounded d-flex justify-content-center align-items-center"
                                id="attachments-dropzone" style="height: 200px;">

                            </div>
                            @if($errors->has('attachments'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('attachments') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.ticket.fields.attachments_helper') }}
                            </p>
                        </div>

                        <div id="tags_div" style="display: none;"
                             class="mb-3 {{ $errors->has('tags') ? 'has-error' : '' }}">
                             <div class="form-group">
                                <label for="file_title"> {{ trans('cruds.ticket.fields.file_title') }}</label>
                                <input type="text" class="form-control" id="file_title" name="file_title"
                                               placeholder="Enter File Title">
                            </div>

                            <div class="form-group">
                                <label for="file_description">{{ trans('cruds.ticket.fields.file_description') }}</label>
                                <textarea type="text" class="form-control" id="file_description" rows="3" name="file_description"
                                               placeholder="Enter File Description"></textarea>
                            </div>

                            <label for="tags">{{ trans('cruds.ticket.fields.tags') }} <strong
                                    class="text-danger">*</strong></label>
                            <select multiple="multiple" name="tags[]" id='tags' class="form-control"></select>
                            @if($errors->has('tags'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('tags') }}
                                </em>
                            @endif
                        </div>
                        @if(!\Auth::user()->isUser())
                            <div class="form-group">
                                <input type="checkbox" id="status" name="status" value="1">
                                <label for="status">Service Delivered</label>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary">@lang('global.submit')</button>
                    </form>
                </td>
            </tr>
            </tbody>
        </table>
        @if (!empty($ticket->ticketFeedbacks))
            <div>
                <h4>What was the feedback?</h4>
                <div class="TicketFeedbacksDiv">
                    @foreach($ticket->ticketFeedbacks as $feedback)
                        <div class="row">
                            <div class="col">
                                @if(isset($feedback['comments']))
                                    <p class="font-weight-bold"><b>Feedback given by:</b> {{ ucwords($ticket->createdBy->name) ?? '' }} <span style="float: right;">(<b>Rating:</b> {{ $feedback['ratings'] }}/5)<span></p>
                                    <p><b>Feedback:</b> {!! $feedback['comments'] !!}</p>
                                @endif
                            </div>
                        </div>
                        <hr/>
                    @endforeach
                </div>
            </div>
        @endif
        <a class="btn btn-default my-2" href="{{ route('admin.tickets.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="btn btn-primary">
            @lang('global.edit') @lang('cruds.ticket.title_singular')
        </a>
    </div>
@endsection
@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let currentCommentPage = 1;
        CKEDITOR.replace('comment_text', {
            filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });

        function initTags() {
            var path = "{{ route('admin.tickets.getTags') }}";
            let data = "{{ implode(',',$ticketTags) }}";
            let tagsSelect = $("#tags").select2({
                placeholder: 'Select Tags',
                tags: true,
                tokenSeparators: [',', ' '],


                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function (data) {
                    return data.html;
                },
                templateSelection: function (data) {
                    if (data && data.text)
                        return data.text;
                },
                ajax: {
                    url: path,
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        // if(data.data != undefined){
                        return {
                            results: $.map(data.data, function (item) {
                                return {
                                    html: "<span style='color:red'>" + item.name + "</span>",
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                        // }
                    },
                    cache: true,

                }
            });
        }

        $(document).ready(function () {

            var tagSelect2 = $('#tags');
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.tickets.getTicketTags',$ticket->id) }}"
            }).then(function (data) {
                // create the option and append to Select2
                let tags = data.data;
                for (let i = 0; i < tags.length; i++) {
                    const option = new Option(tags[i].text, tags[i].id, true, true);
                    $("#tags").append(option).trigger('change');
                }
            });

            $(".btnHideComments").on('click',function () {
                let comment =$(".CommentsDiv div[class='row'],.CommentsDiv hr");
                let commentLength = comment.length;
                let finalLenth = commentLength - 6;
                if(commentLength > 0){
                    let str = '';
                    $(".CommentsDiv").html('');
                    comment.each(function (index,value) {
                        if(finalLenth <= index){
                            $(".CommentsDiv").append(value);
                        }
                    });
                    currentCommentPage = 1;
                    $(".btnPreviousComment").show();
                    $(".btnHideComments").css('display','none');
                }
            });

            $(".btnPreviousComment").on('click', function () {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('admin.tickets.getPreviousComment',$ticket->id) }}",
                    data: {
                        currentCommentPage: currentCommentPage
                    },
                }).then(function (response) {
                    $(".btnHideComments").css('display','none');
                    let len = Object.entries(response.data).length;
                    if (response.status == true && len > 0) {
                        let data = response.data;
                        let str = '';
                        for (let [key, comment] of Object.entries(data)) {
                            str += '<div class="row"> ' +
                                ' <div class="col"> ';
                            const newDate = moment(comment["created_at"]).format('DD-MM-YYYY hh:mm:ss');
                            if (comment['author_email'] != undefined && comment['author_name'] != undefined) {
                                str += '<p class="font-weight-bold"><' +
                                    'a href="mailto:' + comment["author_email"] + '">' + comment["author_name"] + '</a> ' +
                                    '(' + newDate + ')</p>' +
                                    '<p>' + decodeURI(comment["comment_text"]) + '</p>';
                            }else{
                                let baseUrl = "{{ config('constant.base_url') }}";
                                let url = baseUrl+"invoices/"+ comment['id'] +"/"+ comment['file_name'] ;
                                str += '<p class="font-weight-bold">' +
                                    '<a href="mailto:{{ \Illuminate\Support\Facades\Auth::user()->email }}">{{ \Illuminate\Support\Facades\Auth::user()->name }}</a>' +
                                    '('+ newDate + ')</p>'+
                                        '<p class="font-weight-bold">'+
                                        '<a href="'+ url +'" target="_blank" download>'+ comment["name"] +'</a></p>';
                            }
                            str += '</div> ' +
                                '</div> ' +
                                '<hr/>';
                        }
                        $(".CommentsDiv").prepend(str);
                        currentCommentPage++;
                    } else {
                        $(".btnPreviousComment").hide();
                        $(".btnHideComments").css('display','block');
                    }
                });
            });
        });

        let isUser = "{{ \Auth::user()->isUser() ? 'true' : 'false' }}"
        var uploadedAttachmentsMap = {}
        Dropzone.options.attachmentsDropzone = {
            url: '{{ route('admin.tickets.storeMedia') }}',
            addRemoveLinks: true,
            maxFilesize: 10,
            acceptedFiles: ".pdf,.zip",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">')
                uploadedAttachmentsMap[file.name] = response.name
                if (isUser == 'false') {
                    $("#tags_div").css('display', 'block');
                    $("#tags").prop('required', 'true');
                    $("#file_title").prop('required', 'true');
                    $("#file_description").prop('required', 'true');
                    initTags();
                }
            },
            removedfile: function (file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedAttachmentsMap[file.name]
                }
                $('form').find('input[name="attachments[]"][value="' + name + '"]').remove()
                if (isUser == 'false') {
                    $("#tags").removeAttr('required');
                    $("#tags_div").css('display', 'none');
                }
            },
            error: function (file, response) {
                if ($.type(response) === 'string') {
                    var message = response //dropzone sends it's own error messages in string
                } else {
                    var message = response.errors.file
                }
                file.previewElement.classList.add('dz-error')
                _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
                _results = []
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    node = _ref[_i]
                    _results.push(node.textContent = message)
                }

                return _results
            }
        }
    </script>
@endsection
