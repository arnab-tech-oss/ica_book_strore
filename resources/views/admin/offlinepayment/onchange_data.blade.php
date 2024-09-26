<div class="row">
    <div class="col-sm-6">
        <label for="ticekt">Ticket Name <span class="text-danger">*</span></label>
        <input class="form-control form-control-sm" readonly value="{{ $bill->ticket->title ?? '' }}" type="text">
    </div>
    <div class="col-sm-6">
        <label for="ticekt">Client <span class="text-danger">*</span></label>
        <input readonly class="form-control form-control-sm" value="{{ $bill->user->name ?? '' }}" type="text">
    </div>

    <div class="col-sm-4">
        <label for="ticekt">Bill Coast Amount <span class="text-danger">*</span></label>
        <input readonly class="form-control form-control-sm" value="{{ $bill->bill_cost }} " type="text">
    </div>
    <div class="col-sm-4">
        <label for="ticekt">Payment Amount <span class="text-danger">*</span></label>
        <input class="form-control" type="text" name="amount" id="payment"
            max="{{ $bill->bill_cost - $payment_amount }}" min="0"
            value="{{ $bill->bill_cost - $payment_amount }} " max="{{ $bill->bill_cost - $payment_amount }}"
            onkeyup="changeAmount(this,{{ $bill->bill_cost - $payment_amount }})">
        <small class="text-danger" id="payment_error"></small>
    </div>

    <div class="col-sm-4">
        <label for="ticekt">Remaining Amount <span class="text-danger">*</span></label>
        <input required class="form-control" type="text" name="remaining_amount" id="remaining_amount"
            readonly="true">
        <small class="text-danger" id="remaining_amount_error"></small>
    </div>
    <div class="col-sm-4">
        <div class="form-group">
            <label for=""> Mode  of Payment <span class="text-danger">*</span></label>
            <select required="" type="text" id="method" name="method" class="form-control"
                onchange="modeCheck(this.value)">
                <option selected disabled> Payment mode </option>
                <option value="cash" selected="true">Cash</option>
                <option value="cheque">Cheque</option>
                <option value="DD">DD</option>
                <option value="online">Online</option>

            </select>
        </div>
    </div>
    <div class="col-sm-8 ">
        <label for="attachments">You can upload documents (optional)</label>
        <input accept="application/pdf" type="file" name="invoice" class="form-control">


    </div>
    <div class="col-sm-4">
        <div id="none1" class="form-group none">
            <label for="">Cheque/DD No./Transaction No. <span class="text-danger">*</span></label>
            <input required type="text" id="transaction_no" name="transaction_no" class="form-control">
            <small class="text-danger" id="transaction_no_error"></small>

        </div>
    </div>
    <div class="col-sm-4">
        <div id="none2" class="form-group none">
            <label for="">Cheque/DD/Transaction Date <span class="text-danger">*</span></label>
            <input required id="transaction_date" type="datetime-local" name="transaction_date"
                value="{{ date('Y-m-d h:i:s') }}" class="form-control">
            <small class="text-danger" id="transaction_date_error"></small>

        </div>
    </div>
    <div class="col-sm-4">
        <div id="none3" class="form-group none">
            <label for="">Bank Name (optional)</label>
            <input type="text" name="brank_name" class="form-control">
        </div>
    </div>
    <div class="col-sm-4">
        <div id="none4" class="form-group none">
            <label for="">Bank Branch (optional)</label>
            <input type="text" name="bank_branch" class="form-control">
        </div>
    </div>

    <div class="col-sm-12">
        <div class="form-group ">
            <label for="">Remarks <span class="text-danger">*</span></label>
            <textarea type="text" id="notes" name="remarks" class="form-control"></textarea>
            <small class="text-danger" id="notes_error"></small>
        </div>
    </div>
    @if (isset($success))
        <div class="alert alert-success">
            {{ $success }}
        </div>
    @endif
    <div class="text-center mt-4">
        <input class="btn btn-primary btnSubmit" onclick="payment_check()" type="button"
            value="{{ trans('global.submit') }}">
    </div>
</div>
<div>

    <hr>
    <h4 class="text-info text-center border-bottom text-uppercase">All Previous Transactions </h4>
    <span class="text-success"> Total Payment - {{ $payment_amount }}</span>

    <table class="table table-striped table-bordered table-responsive">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Amount</th>
                <th>Mode Of Payment</th>
                <th>Transaction Date</th>
                <th>Transaction No</th>
                <th>Bank Name</th>
                <th>Bank Branch</th>
                <th>Invoice</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($all_payments as $payment)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $payment->amount }}</td>
                    <td>{{ $payment->method }}</td>
                    <td>{{ $payment->transaction_date }}</td>
                    <td>{{ $payment->transaction_no }}</td>
                    <td>{{ $payment->brank_name }}</td>
                    <td>{{ $payment->bank_branch }}</td>
                    <td>
                        @if ($payment->invoice)
                        <a class="btn btn-xs btn-primary m-1" href="{{ asset('invoices')."/" . $payment->invoice }}"
                            target="_blank" download>
                            {{ trans('global.downloadBill') }}
                        </a>
                        @endif

                    </td>
                    <td>{{ $payment->remarks }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>

</div>
