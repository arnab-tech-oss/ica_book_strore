<?php

namespace App\Http\Controllers\Admin;

use App\Bill;
use App\BillTicketServices;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Invoices;
use App\Link;
use App\Mail\SendInvoice;
use App\Service;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use LaravelDaily\Invoices\Classes\Party;
use Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Razorpay\Api\Api;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;


class BillsController extends Controller
{

    public $cc = null, $bcc = null, $account_email = null;

    public function __construct()
    {
        $setting = config('app.setting');
        if (!empty($setting)) {
            $this->cc = explode(',', $setting['email_cc']);
            $this->account_email = explode(",", $setting['account_email']);
            $this->cc = array_merge($this->cc, $this->account_email);
            $this->bcc = explode(',', $setting['email_bcc']);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $bills = Bill::with(['ticket', 'billServices.ticket', 'billServices.services']);
        if (auth()->user()->isDeptUser()) {
            $bills->where('created_by', Auth::user()->id);
        }
        if (auth()->user()->isUser()) {
            $bills->whereHas('ticket', function ($ticket) {
                $ticket->where('created_by', Auth::user()->id);
            });
        }
        $bills = $bills->orderBy('created_at', 'desc')->get();
        return view('admin.bills.index', compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tickets = Ticket::with('ticketServices', 'user');
        if (auth()->user()->isDeptUser() && !auth()->user()->isUser()) {
            $tickets->where('assigned_to_user_id', Auth::user()->id);
        }
        $tickets = $tickets->orderBy('created_at', 'desc')->get();
        return view('admin.bills.create', compact('tickets'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        abort_if(Gate::denies('bill_generate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $service_ids = $request->input('service_ids');
        $ticket_id = $request->input('ticket_id');
        $isSentMail = $request->input('isSentMail');
        $service_ids = explode(",", $service_ids);
        $service_ids = array_filter($service_ids);
        $bill_services = [];
        $services = [];
        $total = 0;
        if ($service_ids) {
            foreach ($service_ids as $value) {
                $cost = $request->input('cost_' . $value);
                if ($cost == null) {
                    $service = Service::find($value);
                    if ($service == null)
                        continue;
                    $cost = $service->cost;
                }
                $remarks = $request->input('remarks_' . $value);
                $services[] = [
                    'ticket_id' => $ticket_id,
                    'service_id' => $value,
                    'updated_cost' => $cost,
                    'remarks' => $remarks ?? null,
                ];
                $total += $cost;
            }
            $bill = Bill::where('ticket_id', $ticket_id)->orderBy('created_at', 'desc')->first();
            if ($bill && $bill->status == Bill::NOT_PAID) {
                Session::flash('message', "This ticket previous bill is pending! You can generate new bill after complete previous one.");
                Session::flash('alert-class', 'alert-danger');
                return redirect()->route('admin.bills.index');
            }
            $taxCost = $total;
            $ticketUser = Ticket::find($ticket_id);
            $buyerUser = User::find($ticketUser->created_by);
            if ($buyerUser->currency == 'INR') {
                $taxCost += (($taxCost * 18) / 100);
            }
            $invoice_id = Invoices::orderBy('id', 'desc')
                ->where('ticket_id', $ticket_id)
                ->where('taxable_amount', (string)$taxCost)
                ->pluck('id')->first();
            $bill = Bill::create([
                'ticket_id' => $ticket_id,
                'bill_cost' => $taxCost,
                'remaining_cost' => $taxCost,
                'created_by' => Auth::user()->id,
                'invoice_id' => $invoice_id ?? null,
                'ref_no' => 'ICCTRD-INV-' . date('y-m') . '-000' . Bill::count('*') + 1

            ]);
            foreach ($services as $key => $value) {
                $services[$key]['bill_id'] = $bill->id;
            }
            BillTicketServices::insert($services);
            if ($isSentMail == 1) {
                $invoice = Invoices::orderBy('id', 'desc')
                    ->where('ticket_id', $ticket_id)
                    ->where('taxable_amount', (string)$taxCost)
                    ->first();
                $files = public_path() . '/' . $invoice->generated_bill_path . '.pdf';
                $bills = Bill::with('ticket.user', 'invoices')->where('id', $bill->id)->first();
                try {
                    Mail::to($bills->ticket->user["email"])->send(new SendInvoice($bills, $files));
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                }
            }
        } else {
            Session::flash('message', "Service Id's are missing!");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.bills.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('bill_generate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bills = Bill::with(['ticket', 'billServices.ticket', 'billServices.services']);
        if (auth()->user()->isDeptUser()) {
            $bills->where('created_by', Auth::user()->id);
        }
        if (auth()->user()->isUser()) {
            $bills->whereHas('ticket', function ($ticket) {
                $ticket->where('created_by', Auth::user()->id);
            });
        }
        $bills = $bills->where('id', $id)->first();
        if ($bills == null) {
            Session::flash('message', "Something is wrong to fetch bill data.");
            Session::flash('alert-class', 'alert-danger');
            return redirect()->back();
        }
        return view('admin.bills.show', compact('bills'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tickets = Ticket::with('ticketServices', 'user');
        if (auth()->user()->isDeptUser() && !auth()->user()->isUser()) {
            $tickets->where('assigned_to_user_id', Auth::user()->id);
        }
        $tickets = $tickets->orderBy('created_at', 'desc')->get();
        $bill = Bill::find($id);
        return view('admin.bills.edit', compact('tickets', 'bill'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('bill_generate_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $service_ids = $request->input('service_ids');
        $ticket_id = $request->input('ticket_id');
        $isSentMail = $request->input('isSentMail');
        $service_ids = explode(",", $service_ids);
        $service_ids = array_filter($service_ids);
        $bill_services = [];
        $services = [];
        $total = 0;
        if ($service_ids) {
            foreach ($service_ids as $value) {
                $cost = $request->input('cost_' . $value);
                if ($cost == null) {
                    $service = Service::find($value);
                    if ($service == null)
                        continue;
                    $cost = $service->cost;
                }
                $remarks = $request->input('remarks_' . $value);
                $services[] = [
                    'ticket_id' => $ticket_id,
                    'service_id' => $value,
                    'updated_cost' => $cost,
                    'remarks' => $remarks ?? null,
                ];
                $total += $cost;
            }
            $taxCost = $total;
            $ticketUser = Ticket::find($ticket_id);
            $buyerUser = User::find($ticketUser->created_by);
            if ($buyerUser->currency == 'INR') {
                $taxCost += (($taxCost * 18) / 100);
            }
            $billId = Bill::where('id', $id)->first();
            if ($billId != null && $billId->invoice_id != null) {
                $billId->invoice_id = null;
                $billId->save();
            }
            $invoice_id = Invoices::orderBy('id', 'desc')
                ->where('ticket_id', $ticket_id)
                ->where('taxable_amount', (string)$taxCost)
                ->pluck('id')->first();
            Bill::where('id', $id)->update([
                'bill_cost' => $total,
                'remaining_cost' => $total,
                'invoice_id' => $invoice_id
            ]);
            foreach ($services as $key => $value) {
                $services[$key]['bill_id'] = $id;
            }
            if ($isSentMail == 1) {
                $bill = Bill::with('invoices')->where('id', $id)->first();
                $files = public_path() . '/' . $bill->invoices->generated_bill_path . '.pdf';
                $bills = Bill::with('ticket.user', 'invoices')->where('id', $bill->id)->first();
                try {
                    Mail::to($bills->ticket->user["email"])->send(new SendInvoice($bills, $files));
                } catch (\Exception $e) {
                    Log::info($e->getMessage());
                }
            }
            BillTicketServices::where('bill_id', $id)->where('ticket_id', $ticket_id)->delete();
            BillTicketServices::insert($services);
        } else {
            Session::flash('message', "Service Id's are missing!");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.bills.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Bill::where('id', $id)->delete();

        return redirect()->route('admin.bills.index');
    }

    public function massDestroy(Request $request)
    {
        Bill::whereIn('id', $request->ids)->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function createBill(Request $request)
    {
        // dd($request);
        $service_ids = $request->input('service_ids');
        $ticket_id = $request->input('ticket_id');
        $isUpdate = $request->input('isUpdate') ?? null;
        $bill_id = $request->input('bill_id') ?? null;
        $service_ids = explode(",", $service_ids);
        $service_ids = array_filter($service_ids);
        $bill_services = [];
        $services = [];
        $total = 0;
        if ($service_ids) {
            foreach ($service_ids as $value) {
                $cost = $request->input('cost_' . $value);
                $service = Service::find($value);
                if ($cost == null) {
                    if ($service == null)
                        continue;
                    $cost = $service->cost;
                }
                $remarks = $request->input('remarks_' . $value);
                $services[] = [
                    'ticket_id' => $ticket_id,
                    'service_id' => $value,
                    'updated_cost' => $cost,
                    'remarks' => $remarks ?? null,
                ];
                $total += $cost;
            }
        }
        $ticketUser = Ticket::find($ticket_id);
        $buyerUser = User::find($ticketUser->created_by);
        $name = $buyerUser->name;
        if ($buyerUser->title != null) {
            $name = $buyerUser->title . ' ' . $buyerUser->name;
        }
        $setting = config('app.setting');
        $client = new Party([
            'name' => $setting['company_name'] ?? 'ICC Tradesk',
            'phone' => $setting['company_contact_no'] ?? ' - ',
            'custom_fields' => [
                'email' => $setting['company_email'] ?? ' - ',
                'gst no' => $setting['company_gst_no'] ?? ' - ',
            ],
        ]);

        $customer = new Party([
            'name' => $name,
            'phone' => $buyerUser->number,
            'custom_fields' => [
                'email' => $buyerUser->email,
                'city' => $buyerUser->city,
            ],
        ]);
        $quantity = 1;

        $items = [];
        $totalAmount = 0;
        foreach ($services as $key => $service) {
            if ($service['service_id'] != null) {
                $serv = Service::find($service['service_id']);
                $discount = $serv->cost < $service['updated_cost'] ? 0 : ($serv->cost - $service['updated_cost']);
                $pricePerUnit = $serv->cost < $service['updated_cost'] ? $service['updated_cost'] : $serv->cost;
                $item = (new InvoiceItem())
                    ->title($serv->name)
                    ->pricePerUnit($pricePerUnit)
                    ->quantity($quantity)
                    ->discount($discount);
                if ($service['remarks'] != null) {
                    $item = $item->description($service['remarks']);
                }
                $items[] = $item;
                $totalAmount += $serv->cost < $service['updated_cost'] ? $service['updated_cost'] : ($serv->cost - ($serv->cost - $service['updated_cost']));
            }
        }
        /*$notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);*/
        $symbol = '$';
        $code = $buyerUser->currency;
        $tax = 0;
        $fraction = 'ct';
        if ($code == null) {
            $code = 'USD';
        }
        if ($buyerUser->currency == 'INR') {
            $symbol = '₹';
            $tax = 18;
            $fraction = 'paisa only';
            $code = 'INR';
        }

        if ($isUpdate) {
            $newBillId = $bill_id;
        } else {
            $newBillId = Bill::orderBy('id', 'desc')->pluck('id')->first();
            $newBillId += 1;
        }
        $todayDate = Carbon::now();
        $invoice_name = str_replace(" ", "_", $name . " " . Carbon::now()->timestamp);
        $invoice = \LaravelDaily\Invoices\Invoice::make('Invoice')
            // ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            ->template('default-view')
            ->status(__('invoices::invoice.paid'))
            ->sequence($newBillId)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date($todayDate)
            ->dateFormat('m/d/Y')
            ->payUntilDays(7)
            ->currencySymbol($symbol)
            ->currencyFraction($fraction)
            ->currencyCode($code)
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.')
            //  ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            // ->notes($notes)
            ->filename($invoice_name)
            ->taxRate($tax)
            ->logo(public_path('img/fav_logo.png'))
            // You can additionally save generated invoice to configured disk
            ->save('public');

        $this->savePDF($newBillId, $client, $customer, $todayDate, $symbol, $fraction, $code, $items, $invoice_name, $tax);
        $link = $invoice->url();
        // Then send email to party with link
        $totalAmountWithTax = ($totalAmount + ($totalAmount * $tax) / 100);
        // And return invoice itself to browser or have a different view
        Invoices::insert([
            'ticket_id' => $ticket_id,
            'amount' => $totalAmount,
            'taxable_amount' => $totalAmountWithTax,
            'tax' => $tax,
            'service_ids' => implode(",", $service_ids),
            'generated_bill_path' => "invoices/" . $invoice_name
        ]);
        return $invoice->toHtml();
    }

    public function savePDF($newBillId, $client, $customer, $todayDate, $symbol, $fraction, $code, $items, $invoice_name, $tax)
    {
        \LaravelDaily\Invoices\Invoice::make('Invoice')
            // ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            ->template('default')
            ->status(__('invoices::invoice.paid'))
            ->sequence($newBillId)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date($todayDate)
            ->dateFormat('m/d/Y')
            ->payUntilDays(7)
            ->currencySymbol($symbol)
            ->currencyFraction($fraction)
            ->currencyCode($code)
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.')
            //  ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            // ->notes($notes)
            ->filename($invoice_name)
            ->taxRate($tax)
            ->logo(public_path('img/fav_logo.png'))
            // You can additionally save generated invoice to configured disk
            ->save('public');
    }

    public function resentInvoice(Request $request, $id)
    {
        if ($id != null) {
            $bill = Bill::with('invoices')->where('id', $id)->first();
            $files = public_path() . '/' . $bill->invoices->generated_bill_path . '.pdf';
            $bills = Bill::with('ticket.user', 'invoices')->where('id', $bill->id)->first();
            try {
                Mail::to($bills->ticket->user["email"])->send(new SendInvoice($bills, $files));
            } catch (\Exception $e) {
                Log::info($e->getMessage());
            }
            Session::flash('message', "Invoice sent on emails successfully.");
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('admin.bills.index');
        }
    }
}
