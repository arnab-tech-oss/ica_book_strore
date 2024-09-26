@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6>{{ trans('global.edit') }} {{ trans('cruds.services.title_singular') }}</h6>
            <form action="{{ route('admin.services.update', [$service->id]) }}" id="serviceForm" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @if ($service->parent_service_id != null)
                    <div class="mb-3">
                        <input type="checkbox"
                            {{ old('sub_category') == true || (isset($service) && $service->parent_service_id != null) ? 'checked' : '' }}
                            class="form-check-input" name="sub_category" id="sub_category">
                        <label for="sub_category" class="form-check-label">Add As Sub Category</label>
                    </div>

                    <div class="mb-3 parent_category" id="parent_category"
                        @if (old('sub_category') != true) style="display: none;" @endif>
                        <div class="{{ $errors->has('parent_service_id') ? 'has-error' : '' }}">
                            <label for="parent_service_id">{{ trans('cruds.services.fields.parent_service_id') }} <strong
                                    class="text-danger">*</strong></label>
                            <select name="parent_service_id" id="parent_service_id" class="form-control select2">
                                <option value="" selected>Select Parent Service Name</option>
                                @foreach ($parentServices as $id => $parent_service)
                                    <option value="{{ $id }}"
                                        {{ old('parent_service_id') == $id || (isset($service) && $service->parent_service_id == $id) ? 'selected' : '' }}>
                                        {{ $parent_service }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('parent_service_id'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('parent_service_id') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.services.fields.parent_service_id_helper') }}
                            </p>
                        </div>
                    </div>
                @endif

                <div class="mb-3 {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">{{ trans('cruds.services.fields.name') }} <strong
                            class="text-danger">*</strong></label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', isset($service) ? $service->name : '') }}" required>
                    @if ($errors->has('name'))
                        <em class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.name_helper') }}
                    </p>
                </div>

                <div class="mb-3 {{ $errors->has('description') ? 'has-error' : '' }}">
                    <label for="description">{{ trans('cruds.services.fields.description') }}</label>
                    <textarea id="description" name="description" class="form-control ">{{ old('description', isset($service) ? $service->description : '') }}</textarea>
                    @if ($errors->has('description'))
                        <em class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.description_helper') }}
                    </p>
                </div>

                <div class="mb-3 {{ $errors->has('service_type') ? 'has-error' : '' }}">
                    <label for="service_type" class="col-4 col-md-2">{{ trans('cruds.services.fields.service_type') }}
                        <strong class="text-danger">*</strong></label>
                    <div class="form-check form-check-inline px-2">
                        <label for="paid_service" class="m-0 mx-2">Paid</label>
                        <input type="radio" id="paid_service" name="service_type"
                            @if ($service->service_type == 'paid') checked @endif class="form-check-input" value="paid"
                            checked>
                    </div>

                    <div class="form-check form-check-inline px-2">
                        <label for="free_service" class="m-0 mx-2">Free</label>
                        <input type="radio" id="free_service" name="service_type"
                            @if ($service->service_type == 'free') checked @endif class="form-check-input" value="free">
                    </div>

                    @if ($errors->has('service_type'))
                        <em class="invalid-feedback">
                            {{ $errors->first('service_type') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.service_type_helper') }}
                    </p>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="mb-3  {{ $errors->has('attachments') ? 'has-error' : '' }}">
                            <label for="attachments">{{ trans('cruds.services.fields.attachments') }}</label>
                            <div class="needsclick dropzone border rounded" id="attachments-dropzone">

                            </div>
                            @if ($errors->has('attachments'))
                                <em class="invalid-feedback">
                                    {{ $errors->first('attachments') }}
                                </em>
                            @endif
                            <p class="helper-block">
                                {{ trans('cruds.services.fields.attachments_helper') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-6">
                        @foreach($service->attachments as $attachment)
                        <a href="{{ asset($attachment->getUrl()) }}" target="_blank">
                            <img  class="img-fluid" src="{{  asset($attachment->getUrl()) }}" alt="">
                        </a>


                        {{-- <a href="{{ $attachment->getUrl() }}" target="_blank">{{ $attachment->file_name }}</a> --}}
                    @endforeach
                    </div>
                </div>

                <div class="mb-3 category_id_div {{ $errors->has('category_id') ? 'has-error' : '' }}">
                    <label for="category_id">{{ trans('cruds.services.fields.category_name') }} <strong
                            class="text-danger">*</strong></label>
                    <select name="category_id" id="category_id" class="form-control select2" required>
                        <option value="" selected>Select Category</option>
                        @foreach ($categories as $id => $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id || $service->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name . ' - ' . $category->id }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('category_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('category_id') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.category_name_helper') }}
                    </p>
                </div>

                <div class="mb-3 {{ $errors->has('currency') ? 'has-error' : '' }}">
                    <label for="currency">{{ trans('cruds.services.fields.currency') }} <strong
                            class="text-danger">*</strong></label>
                    <select name="currency" id="currency" class="form-control select2 currency_input" required>
                        <option value="" selected>Select Currency</option>
                        <option value="INR"
                            {{ old('currency') == 'INR' || $service->currency == 'INR' ? 'selected' : '' }}>INR</option>
                        <option value="USD"
                            {{ old('currency') == 'USD' || $service->currency == 'USD' ? 'selected' : '' }}>USD</option>
                    </select>
                    @if ($errors->has('currency'))
                        <em class="invalid-feedback">
                            {{ $errors->first('currency') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.currency_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('is_payable') ? 'has-error' : '' }}">
                    <label for="is_payable">is payable cost?</label>
                    <input type="checkbox" id="is_payable" value="1" name="is_payable" class="form-check-input"
                        @if (!empty($service->cost)) checked @endif>
                </div>
                <div class="mb-3 {{ $errors->has('cost') ? 'has-error' : '' }}">
                    <label for="cost">{{ trans('cruds.services.fields.cost') }} <strong
                            class="text-danger">*</strong></label>
                    <input type="text" id="cost" name="cost" data-value="{{ $service->cost }}"
                        class="form-control" value="{{ old('cost', isset($service) ? $service->cost : '') }}">
                    @if ($errors->has('cost'))
                        <em class="invalid-feedback">
                            {{ $errors->first('cost') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.cost_helper') }}
                    </p>
                </div>

                <div id="member_cost_div" style="display: none;"
                    class="mb-3 {{ $errors->has('member_cost') ? 'has-error' : '' }}">
                    <label for="member_cost">{{ trans('cruds.services.fields.member_cost') }} <strong
                            class="text-danger">*</strong></label>
                    <input type="text" id="member_cost" data-value="{{ $service->member_cost }}" name="member_cost"
                        class="form-control"
                        value="{{ old('member_cost', isset($service) ? $service->member_cost : '') }}" required>
                    @if ($errors->has('member_cost'))
                        <em class="invalid-feedback">
                            {{ $errors->first('member_cost') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.member_cost_helper') }}
                    </p>
                </div>

                <div class="mb-3 contact_info_div {{ $errors->has('contact_info') ? 'has-error' : '' }}">
                    <label for="contact_info">{{ trans('cruds.services.fields.contact_info') }} <strong
                            class="text-danger">*</strong></label>
                    <textarea id="contact_info" name="contact_info" required class="form-control ">{{ old('contact_info', isset($service) ? $service->contact_info : '') }}</textarea>
                    @if ($errors->has('contact_info'))
                        <em class="invalid-feedback">
                            {{ $errors->first('contact_info') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.contact_info_helper') }}
                    </p>
                </div>

                <div class="mb-3 {{ $errors->has('status') ? 'has-error' : '' }}">
                    <label for="status">{{ trans('cruds.services.fields.status') }} <strong
                            class="text-danger">*</strong></label>
                    <select name="status" id="status" class="form-control select2" required>
                        <option value="" selected>Select Status</option>
                        <option value="1"
                            {{ isset($service->status) && (old('category_id') == $service->status || $service->status == '1') ? 'selected' : '' }}>
                            Active
                        </option>
                        <option value="0"
                            {{ (isset($service->status) && old('category_id') == $service->status) || $service->status == '0' ? 'selected' : '' }}>
                            In Active
                        </option>
                    </select>
                    @if ($errors->has('status'))
                        <em class="invalid-feedback">
                            {{ $errors->first('status') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.status_helper') }}
                    </p>
                </div>

                <div class="mb-3 assigned_user_div {{ $errors->has('assigned_user') ? 'has-error' : '' }}">
                    <label for="assigned_user">{{ trans('cruds.services.fields.assigned_to_users') }} <strong
                            class="text-danger">*</strong></label>
                    <select name="assigned_user" id="assigned_user" class="form-control select2" required>
                        <option value="" selected>Select Users</option>
                        @foreach ($users as $id => $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id') == $user->id || $user->id == $service->assigned_user ? 'selected' : '' }}>
                                {{ $user->name . ' - ' . $user->id }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('assigned_user'))
                        <em class="invalid-feedback">
                            {{ $errors->first('assigned_user') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.services.fields.assigned_to_users_helper') }}
                    </p>
                </div>
                <button class="btn btn-primary serviceSave" type="button">{{ trans('global.update') }}</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function hideDivs(checked = false) {
            if (checked) {
                $(".parent_category").show();
                $(".banner_div").hide();
                $(".category_id_div").hide();
                $(".contact_info_div").hide();
                $(".assigned_user_div").hide();
                $("#category_id").attr('required', false);
                $("#contact_info").attr('required', false);
                $("#assigned_user").attr('required', false);
            } else {
                $(".parent_category").hide();
                $(".banner_div").show();
                $(".category_id_div").show();
                $(".contact_info_div").show();
                $(".assigned_user_div").show();
                $("#category_id").attr('required', true);
                $("#contact_info").attr('required', true);
                $("#assigned_user").attr('required', true);
            }
        }

        function updateCost() {
            if ($("input[type=radio][name=service_type]:checked").val().toLowerCase() == 'free') {
                $("#cost").val(0);
                $("#cost").attr('readonly', 'true');
                if ($("#member_cost_div").is(':visible')) {
                    $("#member_cost").val(0);
                    $("#member_cost").attr('readonly', 'true');
                }
            } else {
                $("#cost").val($("#cost").attr('data-value'));
                $("#cost").removeAttr('readonly');
                if ($("#member_cost_div").is(':visible')) {
                    $("#member_cost").val($("#member_cost").attr('data-value'));
                    $("#member_cost").removeAttr('readonly');
                }
            }
        }

        function getServiceAndSetValues(id) {
            $.ajax({
                url: "{{ route('admin.tickets.getServices') }}",
                data: {
                    service_id: id
                },
                cache: false,
                success: function(html) {
                    if (html !== undefined && html.data !== undefined) {
                        let service = html.data;
                        $('#currency').val(service.currency).attr("disabled", true);
                        if (service.currency == "INR") {
                            $("#member_cost_div").show();
                        }
                        $('#currency').val(service.currency).attr("disabled", true);
                        $('#status').val(service.status).attr("disabled", true);
                        $('#category_id').val(service.category_id);
                        $('#assigned_user').val(service.assigned_user);
                        $('#contact_info').text(service.contact_info);
                    }
                }
            });
        }

        function updatePrefixInCost() {
            let currency_type = $(".currency_input option:selected").text().toLowerCase();
            if (currency_type == 'inr') {
                $("input#cost").val('₹' + $("input#cost").val());
                $("#member_cost_div").show();
                $("input#member_cost").val('₹' + $("input#member_cost").val());
            } else if (currency_type == 'usd') {
                $("input#cost").val('$' + $("input#cost").val());
                $("input#member_cost").val('');
                $("#member_cost_div").hide();
            } else {
                $("input#cost").val($("input#cost").attr('data-value'));
                $("input#member_cost").val('');
                $("#member_cost_div").hide();
            }
        }

        $(document).ready(function() {
            updateCost();
            updatePrefixInCost();
            CKEDITOR.replace('description', {
                filebrowserUploadUrl: "{{ route('ckeditor.image-upload', ['_token' => csrf_token()]) }}",
                filebrowserUploadMethod: 'form'
            });
            if ($("#contact_info").is("visible")) {
                CKEDITOR.replace('contact_info', {
                    filebrowserUploadUrl: "{{ route('ckeditor.image-upload', ['_token' => csrf_token()]) }}",
                    filebrowserUploadMethod: 'form'
                });
            }
            $(".serviceSave").on('click', function() {
                $("#currency").attr("disabled", false);
                $("#status").attr("disabled", false);
                $("#serviceForm").submit();
            });

            $(".currency_input").on('change', function() {
                updateCost();
                updatePrefixInCost();
            });

            $("input[type=radio][name=service_type]").on('change', function() {
                updateCost();
                updatePrefixInCost();
            });


            if ($("#sub_category").prop("checked") == true) {
                hideDivs(true);
            }

            if ($("#parent_service_id").val() != null) {
                let id = $("#parent_service_id").val();
                getServiceAndSetValues(id);
            }

            $("#sub_category").on('change', function() {
                hideDivs($(this).prop("checked"));
            });

            $("#parent_service_id").on("change", function() {
                let id = $(this).val();
                getServiceAndSetValues(id);
            });
        });


        var uploadedAttachmentsMap = {}
        Dropzone.options.attachmentsDropzone = {
            url: '{{ route('admin.services.storeMedia') }}',
            maxFilesize: 2, // MB
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            params: {
                size: 2
            },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="attachments[]" value="' + response.name + '">')
                uploadedAttachmentsMap[file.name] = response.name
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedAttachmentsMap[file.name]
                }
                $('form').find('input[name="attachments[]"][value="' + name + '"]').remove()
            },
            init: function() {
                @if (isset($services) && $services->attachments)
                    var files =
                        {!! json_encode($services->attachments) !!}
                    for (
                        var i in
                            files
                    ) {
                        var file = files[i]
                        this.options.addedfile.call(this, file)
                        file.previewElement.classList.add('dz-complete')
                        $('form').append('<input type="hidden" name="attachments[]" value="' + file.file_name +
                            '">')
                    }
                @endif
            },
            error: function(file, response) {
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
