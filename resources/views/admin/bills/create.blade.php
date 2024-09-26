@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">  {{ trans('global.create') }} {{ trans('cruds.bills.title_singular') }}</h6>
            <div class="alert alert-danger errorMessage_div" style="display: none;">
                <span class="errorMessage"></span>
            </div>
            <form action="{{ route("admin.bills.store") }}" id="billForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 {{ $errors->has('ticket_id') ? 'has-error' : '' }}">
                    <label for="ticket_id">{{ "Service Request Name" }} <strong class="text-danger">*</strong></label>
                    <select name="ticket_id" id="ticket_id" class="form-control select2 ticket_id_change" required>
                        <option value="" selected>Select Ticket</option>
                        @foreach ($tickets as $id => $ticket)
                        {{-- Debug output --}}
                        @if (is_null($ticket->user))
                            Ticket with ID {{ $ticket->id }} has a null user.
                        @endif

                        <option value="{{ $ticket->id }}"
                                data-id="{{ $ticket->id }}"
                                {{ (old('ticket_id') == $ticket->id) ? 'selected' : '' }}>
                            {{ $ticket->user->name ?? '' }} - {{ (strlen($ticket->title) > 15 ? substr($ticket->title, 0, 50)."..." : $ticket->title) }} - {{ \Carbon\Carbon::parse($ticket->created_at)->format('d-m-Y') }} - {{ $ticket->id }}
                        </option>
                    @endforeach

                    </select>
                    @if($errors->has('ticket_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('ticket_id') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.bills.fields.ticket_name_helper') }}
                    </p>
                </div>
                <div class="mb-3 service_div" id="service_div">
                    <label>Services</label>
                </div>

                <div class="mb-3 display_bill border border-4 border-dark p-3 bg-white" style="display: none;"
                     id="display_bill">
                </div>

                <input class="btn btn-primary btn_create" type="button"
                       value="Create {{ trans('cruds.bills.title_singular') }}">
                <input class="btn btn-success btn_submit" style="display:none;" type="submit"
                       value="{{ trans('cruds.bills.title_singular') }} Generate">

                <input type="hidden" name="isSentMail" id="isSentMail" >
                <input class="btn btn-danger btn_submit btn_submit_and_sent_mail" style="display: none;" type="button"
                       value="{{ trans('cruds.bills.title_singular') }} Generate and Mail Sent">
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        let lastServiceIds = '';

        function setCostValue(data) {
            let html = "<label>Services</label>";
            let serviceIds = '';
            let services = data['services'];
            $("#service_div").html('');
            if (services && data['message'] == '') {
                for (let i = 0; i < services.length; i++) {
                    serviceIds = serviceIds + ',' + services[i].id;
                    lastServiceIds = serviceIds;
                    html += '<div class="border d-flex align-items-center justify-content-start service_item_div" id="service_div_' + services[i].id + '">' +
                        '<div class="p-2 form-group">' +
                        '<label for="service">{{ trans('cruds.bills.fields.service') }}</label>' +
                        '<input type="service" id="service" name="service_' + services[i].id + '" class="form-control service_input" value="' + services[i].name + '" disabled>' +
                        '</div>' +

                        '<div class="p-2 form-group">' +
                        '<label for="cost">{{ trans('cruds.bills.fields.cost') }}</label>' +
                        '<input type="number" name="cost_' + services[i].id + '" class="form-control cost_input" id="' + "cost_input_" + services[i].id + '" onkeyup="if(this.value<0){this.value= this.value * -1}" min="0" value="' + services[i].cost + '" disabled required>' +
                        '</div>' +

                        '<div class="p-2 form-group remarks_div" id="' + "remarks_div_" + services[i].id + '" style="display: none;">' +
                        '<label for="remarks'+services[i].id +'">{{ trans('cruds.bills.fields.remarks') }}</label>' +
                        '<input  type="text" id="remarks'+services[i].id +'" name="remarks_' + services[i].id + '" class="form-control remarks_input" value="" >' +
                        '</div>' +

                        '<div class="p-2 form-group my-auto btn_div">' +
                        '<button class="btn btn-primary btn_edit" onclick="showRemarks(' + services[i].id + ')" type="button" value="' + services[i].id + '">{{ trans('global.edit') }} Service {{ trans('cruds.bills.fields.cost') }}</button>' +
                        '</div>' +
                        '<a href="#" class="btn_div_close" onclick="remove(' + services[i].id + ')">Remove</a>' +
                        '</div>';
                }
                html += '<input type="hidden" id="service_ids" name="service_ids" value="' + serviceIds + '" >';
                $("#service_div").html('');
                $("#service_div").append(html);
            } else {
                $('.errorMessage').html(data['message']);
                $(".errorMessage_div").show();
                setTimeout(function () {
                    $(".errorMessage_div").hide();
                }, 2000);
            }
        }

        function showRemarks(id) {
            $("#display_bill").hide();
            $(".btn_create").show();
            $(".btn_submit").hide();
            $("#remarks"+id).attr("required", "true");
            $("#cost_input_" + id).attr('disabled', false);
            $("#remarks_div_" + id).show();
        }

        function getServices(id) {
            $.ajax({
                url: "{{ route('admin.tickets.getServices') }}",
                data: {id: id},
                cache: false,
                success: function (html) {
                    setCostValue(html);
                }
            });
        }

        function remove(id) {
            if ($("#service_div div.service_item_div").length > 1) {
                $("#display_bill").hide();
                $(".btn_create").show();
                $(".btn_submit").hide();
                let ids = $("#service_ids").val();
                ids = ids.split(",");
                for (let i = 0; i < ids.length; i++) {
                    if (ids[i] == id) {
                        ids.splice(i, 1);
                    }
                }
                $("#service_ids").val(ids.join(","));
                $(".service_div #service_div_" + id).remove();
            } else {
                $('.errorMessage').html('At lease one service is required');
                $(".errorMessage_div").show();
                setTimeout(function () {
                    $(".errorMessage_div").hide();
                }, 2000);
            }
        }

        $(document).ready(function () {

            $(".btn_submit_and_sent_mail").on("click",function () {
                $("#isSentMail").val(1);
                $("#billForm").submit();
            });
            $(".ticket_id_change").on('change', function () {
                let id = $(this).find(':selected').data('id');
                getServices(id);
            });

            $(".btn_create").on('click', function () {
                const response = confirm("Confirm all Services Details?");
                console.log(response);
                if (response == true) {

                    let data = $("form").serialize();
                    $.ajax({
                        url: "{{ route('admin.bills.createBill') }}",
                        data: data,
                        cache: false,
                        success: function (html) {
                            $("#display_bill").html(html).show();
                            $(".btn_create").hide();
                            $(".btn_submit").show();
                        }
                    });
                }
            });
        });
    </script>
@endsection
