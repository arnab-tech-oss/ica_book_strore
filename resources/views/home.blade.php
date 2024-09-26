@extends('layouts.admin')
@section('content')

    @if (auth()->user()->isUser() && isset($serviceCards) && !empty($serviceCards))
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Services</h6>
                </div>
                <div class="row flex-row flex-wrap new_servicss ">
                    @foreach ($serviceCards as $key => $service)
                        <div class="col-lg-4 col-md-6 col-xl-4">
                            <div class="bg-white text-start rounded p-4">
                                <a
                                    href="{{ route('services.show', str_replace(' ', '-', str_replace('/', '&#45;&#45;', strtolower($service->name)))) }}">
                                    <div class="text-start">
                                        <h5 class="mb-3 fs-4">{{ $service->name }}</h5>
                                        <h6 class="mb-3">
                                            {{ !empty($service->cost) ? ($service->currency == 'USD' ? '$' : '₹') . $service->cost : '-' }}
                                        </h6>
                                    </div>
                                </a>
                                <a class="theme-btn btn-sm bg-primary"
                                    href="{{ route('admin.tickets.createWithService', $service->id) }}">
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    Add To Cart</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif


    <div class="container-fluid pt-4 px-4">
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-2"
                    style="min-height: 135px;">
                    <i class="fa fa-chart-line fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">WIP support request</p>
                        <h6 class="mb-0">{{ $WIPTickets }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-2"
                    style="min-height: 135px;">
                    <i class="fa fa-chart-bar fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Open Support Request</p>
                        <h6 class="mb-0">{{ $openTickets }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-2"
                    style="min-height: 135px;">
                    <i class="fa fa-chart-area fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Closed Support Request</p>
                        <h6 class="mb-0">{{ $closedTickets }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="bg-light rounded d-flex align-items-center justify-content-between p-2"
                    style="min-height: 135px;">
                    <i class="fa fa-chart-pie fa-3x text-primary"></i>
                    <div class="ms-3">
                        <p class="mb-2">Total Payments</p>
                        <h6 class="mb-0">{{ $totalPayments }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Support Requests</h6>
                <a href="{{ route('admin.tickets.index') }}">Show All</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">

                            <th scope="col">S.NO</th>
                            <th scope="col">Name</th>
                            <th scope="col">Assigned Name</th>
                            <th scope="col">Created Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($supportRequest as $key => $ticket)
                            <tr>

                                <td> {{ $loop->index + 1 ?? '' }}
                                </td>
                                <td>{{ $ticket->title }}</td>
                                <td>{{ !empty($ticket->assigned_to_user) ? $ticket->assigned_to_user->name ?? '-' : '-' }}
                                </td>
                                <td>{{ !empty($ticket->user) ? $ticket->user->name ?? '-' : '-' }}</td>
                                <td>{{ !empty($ticket->status) ? $ticket->status->name ?? '-' : '-' }}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ route('admin.tickets.show', $ticket->id) }}">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Recent Sales End -->

    <!-- Recent Sales Start -->
    <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="mb-0">Recent Payments</h6>
                <a href="{{ route('admin.payment.index') }}">Show All</a>
            </div>
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">

                            <th scope="col">S.NO</th>
                            <th scope="col">Payment Id.</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Status</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $key => $payment)
                            <tr>
                                <td> {{ $loop->index + 1 ?? '' }}
                                </td>
                                <td>{{ $payment->payment_id }}</td>
                                <td>{{ !empty($payment->bill) && !empty($payment->bill->user) ? $payment->bill->user->name ?? '-' : '-' }}
                                </td>
                                <td class="text-capitalize">{{ $payment->payment_link_status }}</td>
                                <td>{{ $payment->amount }}</td>
                                <td>
                                    @if (isset($payment->bill) && isset($payment->bill->invoices) && !empty($payment->bill->invoices))
                                        @can('bill_generate_access')
                                            <a class="btn btn-xs btn-primary m-1"
                                                href="{{ config('constant.base_url') . $payment->bill->invoices->generated_bill_path . '.pdf' }}"
                                                target="_blank" download>
                                                {{ trans('global.downloadBill') }}
                                            </a>
                                            <a class="btn btn-sm btn-primary"
                                                href="{{ route('admin.bills.show', $payment->bill->id) }}">Bill Detail</a>
                                        @endcan
                                    @endif
                                    <a class="btn btn-sm btn-primary"
                                        href="{{ route('admin.payment.show', $payment->id) }}">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- @if (auth()->user()->isUser() && isset($serviceCards) && !empty($serviceCards))
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Services</h6>
                </div>
                <div class="row flex-row flex-nowrap overflow-auto">
                    @foreach ($serviceCards as $key => $service)
                        <div class="col-lg-4 col-md-6 col-xl-4">
                            <div class="bg-white text-start rounded p-4">
                                <a
                                    href="{{ route('services.show', str_replace(' ', '-', str_replace('/', '&#45;&#45;', strtolower($service->name)))) }}">
                                    <div class="text-start">
                                        <h5 class="mb-3">{{ $service->name }}</h5>
                                        <h6 class="mb-3">
                                            {{ !empty($service->cost) ? ($service->currency == 'USD' ? '$' : '₹') . $service->cost : '-' }}
                                        </h6>
                                    </div>
                                </a>
                                <a class="theme-btn btn-sm bg-primary"
                                    href="{{ route('admin.tickets.createWithService', $service->id) }}">
                                    <i class="fa fa-shopping-cart me-2"></i>
                                    Add To Cart</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif --}}

@endsection
@section('scripts')
    @parent
@endsection
