@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.edit') }} {{ trans('cruds.ticket.title_singular') }}</h6>
            <form action="{{ route("admin.tickets.update", [$ticket->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group {{ $errors->has('service_id') ? 'has-error' : '' }}">
                    <label for="service_id">{{ trans('cruds.ticket.fields.service') }} <strong class="text-danger">*</strong></label>
                    <input type="hidden" id="service_id_hidden"
                           value="{{ implode(",",$ticket->ticketServices->pluck('id')->toArray()) }}">
                    <select name="service_id[]" id="service_id" class="form-control select2 serviceOptions" size="5"
                            style="height: 100%;" multiple="multiple" required>
                        @if($services != null)
                            @foreach($services as $id => $service)
                                <option value="{{ $service->id }}" data-assigned-user="{{ $service->assigned_user }}"
                                        @if(isset($ticket) && $ticket->ticketServices->contains($service->id)) selected @endif>{{ $service->name .' - '. $service->id .' - '. $service->cost }}</option>
                            @endforeach
                        @endif
                    </select>
                    @if($errors->has('service_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('service_id') }}
                        </em>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                    <label for="title">{{ trans('cruds.ticket.fields.title') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="title" name="title" class="form-control"
                           value="{{ old('title', isset($ticket) ? $ticket->title : '') }}" required>
                    @if($errors->has('title'))
                        <em class="invalid-feedback">
                            {{ $errors->first('title') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.ticket.fields.title_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('content') ? 'has-error' : '' }}">
                    <label for="content">{{ trans('cruds.ticket.fields.content') }}</label>
                    <textarea id="content" name="content"
                              class="form-control ">{{ old('content', isset($ticket) ? $ticket->content : '') }}</textarea>
                    @if($errors->has('content'))
                        <em class="invalid-feedback">
                            {{ $errors->first('content') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.ticket.fields.content_helper') }}
                    </p>
                </div>
                <div class="form-group {{ $errors->has('attachments') ? 'has-error' : '' }}">
                    <label for="attachments">{{ trans('cruds.ticket.fields.attachments') }}( <span class="text-danger">zip and pdf only</span> )</label>
                    <div class="needsclick dropzone border rounded d-flex justify-content-center align-items-center"
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
                <div class="form-group {{ $errors->has('status_id') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.ticket.fields.status') }} <strong class="text-danger">*</strong></label>
                    <select name="status_id" id="status" class="form-control select2" required>
                        @foreach($statuses as $id => $status)
                            <option
                                value="{{ $id }}" {{ (isset($ticket) && $ticket->status ? $ticket->status->id : old('status_id')) == $id ? 'selected' : '' }}>{{ $status }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('status_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('status_id') }}
                        </em>
                    @endif
                </div>
                @if(auth()->user()->isAdmin() || auth()->user()->isDeptAdmin())
                    <div class="form-group {{ $errors->has('assigned_to_user_id') ? 'has-error' : '' }}">
                        <label for="assigned_to_user">{{ trans('cruds.ticket.fields.assigned_to_user') }}</label>
                        <select name="assigned_to_user_id"  class="form-control select2">
                            @foreach($assigned_to_users as $id => $assigned_to_user)
                                <option value="{{ $id }}" {{ $ticket->assigned_to_user_id == $id ? 'selected':''}} >{{ $assigned_to_user }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('assigned_to_user_id'))
                            <em class="invalid-feedback">
                                {{ $errors->first('assigned_to_user_id') }}
                            </em>
                        @endif
                    </div>
                @endif
                <div>
                    <div class="form-group">
                        {{-- <div class="overflow-auto my-2 px-3 bg-white rounded border border-dark" style="max-height: 250px;">
                            <p>{!! $terms->content !!}</p>
                        </div> --}}
                        <input type="checkbox" name="terms" id="terms" class="form-check-input" required>
                        <label for="terms">I agree to these Terms and Conditions.</label>
                    </div>
                    <input class="btn btn-primary" type="submit" value="{{ trans('global.update') }}">
                </div>
            </form>


        </div>
    </div>
@endsection

@section('scripts')
    <script>

        function getServices(id) {
            if (id) {
                $.ajax({
                    url: "{{ route('admin.tickets.getServices') }}",
                    data: {user_id: id},
                    cache: false,
                    success: function (html) {
                        setServiceOptions(html);
                    }
                });
            }
        }

        function setServiceOptions(data) {
            let services = data['services'];
            let currency = data['currency'];
            let alreadyServices = $("#service_id_hidden").val();
            if (services.length > 0 && currency != null) {
                let html = "";
                let currency_symbol = "&#36;";
                if (currency == "INR") {
                    currency_symbol = "&#8377;";
                    for (let i = 0; i < services.length; i++) {
                        let date = new Date(services[i]['created_at']);
                        var month = date.getMonth() + 1;//months (0-11)
                        var day = date.getDate();//day (1-31)
                        var year = date.getFullYear();
                        var formattedDate = day + "-" + month + "-" + year;
                        let price = services[i]["member_cost"] != null && services[i]["member_cost"] != 0 ? services[i]["member_cost"] : services[i]["cost"];
                        html += `<option value="${services[i]["id"]}" ${alreadyServices.includes(services[i]["id"]) ? 'selected' : ''}>${services[i]["name"]}  - ${formattedDate} - ${services[i]["id"]}  -  ${currency_symbol} ${price}</option>`;
                    }
                } else {
                    for (let i = 0; i < services.length; i++) {
                        let date = new Date(services[i]['created_at']);
                        var month = date.getMonth() + 1;//months (0-11)
                        var day = date.getDate();//day (1-31)
                        var year = date.getFullYear();
                        var formattedDate = day + "-" + month + "-" + year;
                        html += `<option value="${services[i]["id"]}" ${alreadyServices.includes(services[i]["id"]) ? 'selected' : ''}>${services[i]["name"]}  - ${formattedDate} -  ${services[i]["id"]}  -  ${currency_symbol} ${services[i]["cost"]}</option>`;
                    }
                }
                $(".serviceOptions").html('');
                $(".serviceOptions").html(html);
            }
        }

        /*$(document).ready(function () {
            if ($("#assined_user_main_div").length === 0) {
                let userId = "{{ \Illuminate\Support\Facades\Auth::user()->id }}";
                getServices(userId);
            }
        });*/

        // $("#assigned_to_user").on('change', function () {
        //     let user_id = $(this).val();
        //     getServices(user_id);
        // });
        //
        // $("#assigned_to_user").on('change', function () {
        //     let user_id = $(this).val();
        //     getServices(user_id);
        // });

        CKEDITOR.replace('content', {
            filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });

        $("select#service_id").on('change',function () {
            let value = $(this).val();
            let assigned_user_id = $("select#service_id option:selected").first().attr('data-assigned-user');
            if(assigned_user_id != null){
                $("select#assigned_to_user").val(assigned_user_id);
            }
        });

        var uploadedAttachmentsMap = {}
        Dropzone.options.attachmentsDropzone = {
            url: '{{ route('admin.tickets.storeMedia') }}',
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">')
                uploadedAttachmentsMap[file.name] = response.name
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
            },
            init: function () {
                @if(isset($ticket) && $ticket->attachments)
                var files =
                    {!! json_encode($ticket->attachments) !!}
                    for(
                var i
            in
                files
            )
                {
                    var file = files[i]
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name + '">')
                }
                @endif
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
     <script type="text/javascript">
        $(function() {
            $('#service_id').multiSelect();
        });
    </script>
    <style>
        .multi-select-container {
          width: 100%;
        }
      </style>
@stop
