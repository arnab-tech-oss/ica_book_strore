@extends('layouts.admin')
@section('content')

    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.create') }} {{ trans('cruds.payment.title_singular') }}</h6>
            <form id="PaymentForm" action="{{ route("admin.payment.store") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 {{ $errors->has('ticket_id') ? 'has-error' : '' }}">
                    <label for="ticket_id">{{ trans('cruds.payment.fields.ticket_name') }} <strong class="text-danger">*</strong></label>
                    <select name="ticket_id" id="ticket_id" class="form-control select2 ticket_id_change" required>
                        <option value="" selected>Select Ticket</option>
                        @foreach($tickets as $id => $ticket)
                            <option value="{{ $ticket->id }}" data-client="{{ $ticket->user->name }}" data-bills="{{ $ticket->bills }}" {{ (old('ticket_id') == $ticket->id) ? 'selected' : '' }}>{{ $ticket->id.' - '.$ticket->title.' - '. $ticket->user->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('ticket_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('ticket_id') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.payment.fields.ticket_name_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('bill_id') ? 'has-error' : '' }}">
                    <label for="bill_id">{{ trans('cruds.payment.fields.bill_id') }} <strong class="text-danger">*</strong></label>
                    <select name="bill_id" id="bill_id" class="form-control select2 bill_id_options" required>
                        <option value="" selected>Select Bill</option>
                    </select>
                    @if($errors->has('bill_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('bill_id') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.payment.fields.bill_id_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('amount') ? 'has-error' : '' }}">
                    <label for="amount">{{ trans('cruds.payment.fields.amount') }} <strong class="text-danger">*</strong></label>
                    <input type="number" id="amount" name="amount" class="form-control amount_elem" min="0" value="" readonly>
                    @if($errors->has('amount'))
                        <em class="invalid-feedback">
                            {{ $errors->first('amount') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.payment.fields.amount_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('payment_id') ? 'has-error' : '' }}">
                    <label for="payment_id">{{ trans('cruds.payment.fields.payment_id') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="payment_id" name="payment_id" class="form-control" value="" required>
                    @if($errors->has('payment_id'))
                        <em class="invalid-feedback">
                            {{ $errors->first('payment_id') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.payment.fields.payment_id_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('payment_method') ? 'has-error' : '' }}">
                    <label for="payment_method">{{ trans('cruds.payment.fields.payment_method') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="payment_method" name="payment_method" class="form-control" value="offline" readonly>
                    @if($errors->has('user'))
                        <em class="invalid-feedback">
                            {{ $errors->first('payment_method') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.payment.fields.payment_method_helper') }}
                    </p>
                </div>
                <div class="mb-3 {{ $errors->has('user') ? 'has-error' : '' }}">
                    <label for="user">{{ trans('cruds.payment.fields.user') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="user" name="user" class="form-control user_input" value="" readonly>
                    @if($errors->has('user'))
                        <em class="invalid-feedback">
                            {{ $errors->first('user') }}
                        </em>
                    @endif
                    <p class="helper-block">
                        {{ trans('cruds.payment.fields.user_helper') }}
                    </p>
                </div>

                <input class="btn btn-primary btnSubmit" type="submit" value="{{ trans('global.submit') }}">
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $(".ticket_id_change").on('change',function () {
                let ticketId = $(this).val();
                if(ticketId){
                    let bills = $("option[value="+ticketId+"]").attr('data-bills');
                    if(typeof bills == "string"){
                        bills = JSON.parse(bills);
                    }
                    if(bills.length > 0){
                        let html = '';
                        for (const key in bills) {
                            let cost = bills[key]['status'] === 'not_paid' ? bills[key]['bill_cost'] : bills[key]['remaining_cost'];
                            html += '<option data-cost="'+ cost +'" value="'+bills[key]['id']+'">Bill Id - '+ (bills[key]['id']+" Bill Cost - "+cost)+'</option>';
                        }
                        $(".bill_id_options").append(html);
                        let clientName = $("option[value="+ticketId+"]").attr('data-client');
                        $(".user_input").val(clientName);
                    }
                }else{
                    $(".user_input").val('');
                    $('.bill_id_options option:not(:first)').remove();
                }
            });

            $(".bill_id_options").on('change',function () {
                let billId = $(this).val();
                if(billId){
                    let cost = $("option[value="+billId+"]").attr('data-cost');
                    if(cost){
                       $(".amount_elem").attr('max',cost);
                       $(".amount_elem").val(cost);
                    }
                }else{
                    $(".amount_elem").attr('max',0);
                    $(".amount_elem").val('');
                }
            });
        });
    </script>
@endsection
