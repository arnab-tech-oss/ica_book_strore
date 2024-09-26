<?php

namespace App\Http\Controllers\Admin;

use App\Bill;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Link;
use App\Payments;
use App\Priority;
use App\Role;
use App\RoleUser;
use App\Service;
use App\Status;
use App\Ticket;
use App\TicketServices;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payments::with(['bill.ticket.user','bill.billServices']);
        if(auth()->user()->isUser()){
            $payments->whereHas('bill.ticket',function($user) {
                $user->where('created_by', Auth::user()->id);
            });
        }else if(!auth()->user()->isAdmin() && auth()->user()->isDeptUser()){
            $payments->whereHas('bill.ticket',function($ticket){
                $ticket->where('assigned_to_user_id',Auth::user()->id);
            });
        }
        $payments = $payments->get();

        return view('admin.payments.index',compact('payments'));
    }

    public function create()
    {
        abort_if(\Gate::denies('payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tickets = Ticket::select('tickets.*')->join('bill','bill.ticket_id','tickets.id')->with(['user','status' => function ($query) {
                $query->whereIn('name', ['Open', 'WIP']);
            }])->whereIn('bill.status',['partially_paid','not_paid'])->get();
        return view('admin.payments.create', compact('tickets'));
    }

    public function store(Request $request)
    {
        $payment_id = $request->input('payment_id');
        $ticket_id = $request->input('ticket_id');
        $bill_id = $request->input('bill_id');
        $amount = $request->input('amount');
        $payment_method = $request->input('payment_method');
        $user = $request->input('user');
        Payments::insert([
            'payment_id' => $payment_id,
            'payment_link_id' => null,
            'payment_link_reference_id' => null,
            'payment_link_status' => 'paid',
            'signature' => null,
            'method' => $payment_method,
            'amount' => $amount,
            'bill_id' => $bill_id
        ]);
        if (!empty($payment_id)) {
            $bill = Bill::where('id', $bill_id)->first();
            $paidCost = $amount;
            if ($bill->remaining_cost != 0) {
                $paidCost = $bill->remaining_cost - $amount;
            } else {
                $paidCost = $bill->bill_cost - $paidCost;
            }
            $status = Bill::PARTIALLY_PAID;
            if ($bill->bill_cost == $paidCost) {
                $status = Bill::FULLY_PAID;
            }
            $bill = Bill::where('id', $bill_id)->update([
                'remaining_cost' => $paidCost,
                'status' => $status
            ]);
            $link = Link::where('bill_id', $bill_id)->update([
                'payment_status' => $status
            ]);
            Session::flash('message', 'Your Payment is Successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect()->route('admin.payment.index');
        } else {
            Session::flash('message', 'Your Payment is Failed');
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('admin.payment.index');
        }
    }

    public function edit(Payments $payments)
    {
        //
    }

    public function update(Request $request, Payments $payments)
    {
        //
    }

    public function show(Payments $payment)
    {
        abort_if(Gate::denies('payment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $payment->with('bill')->get();
        return view('admin.payments.show', compact('payment'));
    }

    public function destroy(Payments $payments)
    {
       //
    }
}
