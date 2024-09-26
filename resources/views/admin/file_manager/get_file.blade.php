<div class="row">
    @foreach ($data as $invoice)
        <div class="col-6 col-md-3 mb-3">
            <div class="card ">
                <div class="p-2 ">
                    <iframe src="{{ asset('invoices/' . $invoice->id . '/' . $invoice->file_name) }}" class="card-img-top"
                        alt="..."></iframe>
                </div>
                <div class="ps-1 pe-1 text-center">
                    <a download="{{ $invoice->file_name }}"
                        href="{{ asset('invoices/' . $invoice->id . '/' . $invoice->file_name) }}" class="card-text">{{ Str::substr($invoice->file_name, 0, 30) }} .. <i class="fa fa-download" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach

</div>
