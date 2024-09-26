@extends('layouts.admin')
@section('content')
    <div class="col-12">
        <div class="bg-light rounded h-100 p-4">
            <h6 class="mb-4">{{ trans('global.create') }} {{ trans('cruds.links.title_singular') }}</h6>
            <form id="LinkForm" action="{{ route('admin.links.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class=" col-sm-12 mb-3 {{ $errors->has('bill_id') ? 'has-error' : '' }}">
                        <label for="bill_id">{{ trans('cruds.links.fields.bill_name') }} <strong
                                class="text-danger">*</strong></label>

                        <select
                            onchange="ajaxCall('LinkForm', '{{ route('payment.offline') }}/onchange/'+this.value, 'success_data','GET')"
                            name="bill_id" id="bill_id" class="form-control form-control-sm select2 ticket_id_change"
                            required>
                            @if ($bills->count('*') > 1)\
                            <option disabled selected>Select Bill</option>

                                @foreach ($bills as $bill)
                                    <option value="{{ $bill->id }}"> {{ $bill->ticket->title }} -
                                        {{ $bill->user->name }}
                                    </option>
                                @endforeach
                            @else
                                <option value="{{ $bills[0]->id }}"> {{ $bills[0]->ticket->title }} -
                                    {{ $bills[0]->user->name }}
                            @endif
                        </select>
                    </div>
                    <span id="success_data">
                        @if (isset($view))
                            {!! $view !!}
                        @endif
                    </span>

            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function modeCheck(mode) {
            if (mode != 'cash') {
                console.log(mode);
                document.getElementById('none1').style.display = 'block';
                document.getElementById('none2').style.display = 'block';
                document.getElementById('none3').style.display = 'block';
                document.getElementById('none4').style.display = 'block';

            } else {
                document.getElementById('none1').style.display = 'none';
                document.getElementById('none2').style.display = 'none';
                document.getElementById('none3').style.display = 'none';
                document.getElementById('none4').style.display = 'none';
            }
        }

        function changeAmount(data, totalRemainingAmount) {
            if (totalRemainingAmount - data.value >= 0) {
                document.getElementById('remaining_amount').value = totalRemainingAmount - data.value;
            }
            if (data.value > totalRemainingAmount) {
                data.value = totalRemainingAmount;
            }
        }

        function payment_check() {
            error = true;
            var payment_amount = document.getElementById('payment');
            if (payment_amount.value == '' || payment_amount.value <= 0) {
                payment_amount.style.borderColor = "red";
                document.getElementById('payment_error').innerText = "Value must be greater than 0"
                error = false;

            } else {
                payment_amount.style.borderColor = "green";
                document.getElementById('payment_error').innerText = ""
                error = true
            }

            var remaining_amount = document.getElementById('remaining_amount');
            if (remaining_amount.value == '' || remaining_amount.value <= -1) {
                remaining_amount.style.borderColor = "red";
                document.getElementById('remaining_amount_error').innerText = "Value must be greater than and equal 0"
                error = false;
            } else {
                remaining_amount.style.borderColor = "green";
                document.getElementById('remaining_amount_error').innerText = ""
                error = true;
            }






            var method = document.getElementById('method');
            if (method.value != 'cash') {
                var transaction_no = document.getElementById('transaction_no');
                if (transaction_no.value == '' || transaction_no.value <= 0) {
                    transaction_no.style.borderColor = "red";
                    document.getElementById('transaction_no_error').innerText = "Please Enter the valid Transaction no"
                    error = false;
                } else {
                    transaction_no.style.borderColor = "green";
                    document.getElementById('transaction_no_error').innerText = ""
                    error = true;
                }
            }

            var transaction_date = document.getElementById('transaction_date');
            if (transaction_date.value == '' || transaction_date.value <= 0) {
                transaction_date.style.borderColor = "red";
                document.getElementById('transaction_date_error').innerText = "Please Enter the date and time "
                error = false;


            } else {
                transaction_date.style.borderColor = "green";
                document.getElementById('transaction_date_error').innerText = ""
                error = true;
            }

            var notes = document.getElementById('notes');
            if (notes.value == '' || notes.value <= 0) {
                notes.style.borderColor = "red";
                document.getElementById('notes_error').innerText = "Please write something about the payment"
                error = false;


            } else {
                notes.style.borderColor = "green";
                document.getElementById('notes_error').innerText = ""
                error = true;
            }


            if (error == true) {
                ajaxCall('LinkForm', '{{ route('payment.offline') }}', 'success_data')
                // window.location.href = '{{ route('payment.offline') }}';
            }

        }
    </script>
    <style>
        .none {
            display: none;
        }
    </style>
@endsection
