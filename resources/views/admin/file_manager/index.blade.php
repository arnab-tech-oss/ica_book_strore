@extends('layouts.admin')
@section('content')
    <div class="container-fluid pt-4 px-4">
        <form id="form_data" method="post">
            @csrf
            <input class="form-control rounded-pill "
                onkeyup="ajaxCall('form_data', '{{ route('file.manager.get') }}', 'success_data')" name="search"
                type="text" placeholder="Search File ..."> <input type="hidden">
        </form>

    </div>
    <div id="success_data" class="container mt-4">
        <div class="row">
            @foreach ($data as $invoice)
                <div class="col-6 col-md-3 mb-3">
                    <div class="card ">
                        <div class="p-2 ">
                            <iframe
                                src="{{asset('invoices/'.$invoice->id."/".$invoice->file_name) }}"
                                class="card-img-top" alt="..."></iframe>
                        </div>
                        <div class="ps-1 pe-1 text-center">
                            <a download="{{ $invoice->file_name }}"
                                href="{{ asset('invoices/' . $invoice->id . '/' . $invoice->file_name) }}" class="card-text">{{ Str::substr($invoice->file_name, 0, 30) }} .. <i class="fa fa-download" aria-hidden="true"></i>
                            </a>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
        <div class="text-center mt-3">
            {{ $data->links() }}
        </div>
    </div>
@endsection
