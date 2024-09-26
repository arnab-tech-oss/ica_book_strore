@extends('layouts.admin')
@section('content')
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.create') }} {{ trans('cruds.links.title_singular') }}</h6>
            <form id="LinkForm" action="{{ route('admin.links.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 {{ $errors->has('bill_id') ? 'has-error' : '' }}">
                    <label for="bill_id">{{ trans('cruds.links.fields.bill_name') }} <strong
                            class="text-danger">*</strong></label>
                    <select name="bill_id" id="bill_id" class="form-control select2 ticket_id_change" required>
                        <option value="" selected>Select Bill</option>
                        @foreach ($bills as $id => $bill)
                            <option value="{{ $bill->id }}" data-ticket="{{ $bill->ticket->title }}"
                                data-cost="{{ $bill->bill_cost }}" data-remaining="{{ $bill->remaining_cost }}"
                                data-user="{{ $bill->user->name }}" {{ old('bill_id') == $bill->id ? 'selected' : '' }}>
                                {{ $bill->user->name . ' - ' . (strlen($bill->ticket->title) > 15 ? substr($bill->ticket->title, 0, 50) . '...' : $bill->ticket->title) . ' - ' . \Carbon\Carbon::parse($bill->created_at)->format('d-m-Y') . ' - ' . $bill->id }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('bill_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('bill_id') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.links.fields.ticket_name_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('ticket') ? 'has-error' : '' }}">
                    <label for="ticket">{{ trans('cruds.links.fields.ticket_name') }} <strong
                            class="text-danger">*</strong></label>
                    <input type="ticket" id="ticket" name="ticket" class="form-control ticket_input" value=""
                        disabled>
                    @if ($errors->has('ticket'))
                        <em class="invalid-feedback">
                            {{ $errors->first('ticket') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.links.fields.ticket_name_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('user') ? 'has-error' : '' }}">
                    <label for="user">{{ trans('cruds.links.fields.user') }} <strong
                            class="text-danger">*</strong></label>
                    <input type="user" id="user" name="user" class="form-control user_input" value=""
                        disabled>
                    @if ($errors->has('user'))
                        <em class="invalid-feedback">
                            {{ $errors->first('user') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.links.fields.user_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('cost') ? 'has-error' : '' }}">
                    <label for="cost">{{ trans('cruds.links.fields.cost') }} <strong
                            class="text-danger">*</strong></label>
                    <input type="number" id="cost" name="cost" class="form-control cost_input" value=""
                        min="100" disabled required>
                    <button class="btn btn-primary btn_edit" type="button">{{ trans('global.edit') }}
                        {{ trans('cruds.links.fields.cost') }}</button>
                    @if ($errors->has('cost'))
                        <em class="invalid-feedback">
                            {{ $errors->first('cost') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.links.fields.cost_helper') }}
                    </p>
                </div>
                <div class="mb-3 remarks_div {{ $errors->has('remarks') ? 'has-error' : '' }}" style="display: none;">
                    <label for="remarks">{{ trans('cruds.links.fields.remarks') }} <strong
                            class="text-danger">*</strong></label>
                    <input type="text" id="remarks" name="remarks" class="form-control remarks_input" value="">
                    @if ($errors->has('remarks'))
                        <em class="invalid-feedback">
                            {{ $errors->first('remarks') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.links.fields.remarks_helper') }}
                    </p>
                </div>

                <input class="btn btn-primary btnSubmit" type="button" value="{{ trans('global.submit') }}">
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function setCostValue(val, ticket, user, remaining) {
            let actualCost = val;
            if (parseInt(remaining) != 0) {
                let alreadyPaid = (parseInt(val) - parseInt(remaining));
                actualCost = (parseInt(val) - alreadyPaid);
            }
            $(".cost_input").val(actualCost);
            $(".ticket_input").val(ticket);
            $(".user_input").val(user);
        }
        let cost = $(".ticket_id_change").find(':selected').data('cost');
        let ticketName = $(".ticket_id_change").find(':selected').data('ticket');
        let userName = $(".ticket_id_change").find(':selected').data('user');
        let remaining = $(".ticket_id_change").find(':selected').data('remaining');
        setCostValue(cost, ticketName, userName, remaining);
        $(document).ready(function() {

            $(".btnSubmit").on('click', function() {
                $(".cost_input").prop("disabled", false);
                $('form#LinkForm').submit();
            });

            $(".btn_edit,.btn_submit").on('click', function() {
                $(".cost_input").attr('disabled', false);
                $(".remarks_div").show();
            });
            $(".ticket_id_change").on('change', function() {
                let cost = $(this).find(':selected').data('cost');
                let ticketName = $(this).find(':selected').data('ticket');
                let userName = $(this).find(':selected').data('user');
                let remaining = $(this).find(':selected').data('remaining');
                setCostValue(cost, ticketName, userName, remaining);
            })
        });
    </script>
@endsection
