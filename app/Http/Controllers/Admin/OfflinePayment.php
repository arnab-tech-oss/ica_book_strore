<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Payments;
use App\Bill;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OfflinePayment extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // abort_if(Gate::denies('payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bills = Bill::with('billServices', 'ticket', 'user');
        if (auth()->user()->isDeptUser()) {
            $bills->where('created_by', Auth::user()->id);
        }
        $bills = $bills->orderBy('created_at', 'desc')->get();
        return view('admin.offlinepayment.create', ['bills' => $bills]);
    }
    public function index1($id)
    {
        // abort_if(Gate::denies('payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $bills = Bill::with('billServices', 'ticket', 'user');
        if (auth()->user()->isDeptUser()) {
            $bills->where('created_by', Auth::user()->id);
        }
        $bills = $bills->orderBy('created_at', 'desc')->where('id', $id)->first();
        $all_payment = Payments::where('bill_id', $id)->get();
        $sum_payment_amount = Payments::where('bill_id', $id)->sum('amount');
        $view=view('admin.offlinepayment.onchange_data',['bill' => $bills, 'all_payments' => $all_payment, 'payment_amount' => $sum_payment_amount]);
        $bills = $bills->where('id', $id)->get();

        return view('admin.offlinepayment.create', ['bills' => $bills,'view'=>$view]);
    }

    public function onchange_data($id)
    {
        // abort_if(Gate::denies('payment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bills = Bill::with('billServices', 'ticket', 'user');
        if (auth()->user()->isDeptUser()) {
            $bills->where('created_by', Auth::user()->id);
        }
        $bills = $bills->orderBy('created_at', 'desc')->where('id', $id)->first();
        $all_payment = Payments::where('bill_id', $id)->get();
        $sum_payment_amount = Payments::where('bill_id', $id)->sum('amount');

        return view('admin.offlinepayment.onchange_data', ['bill' => $bills, 'all_payments' => $all_payment, 'payment_amount' => $sum_payment_amount]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dump($request->all());

        $validator = $this->validate($request, [
            'bill_id' => 'required',
            'amount' => 'required|numeric',
            'remaining_amount' => 'required|numeric',
            'method' => 'required',
            'transaction_date' => 'required'
        ]);



        if ($validator) {
            $id = $request->bill_id;

            $payment_id =  Payments::insertGetId($request->except('_token'));
            Bill::where('id', $id)->update([
                'remaining_cost' => $request->remaining_amount,
                'status' => 'partially_paid'
            ]);
            if ($request->file('invoice')) {
                Payments::where('id', $payment_id)->update(['invoice' => $this->insert_image($request->file('invoice'), 'invoices')]);
            }
            $bills = Bill::with('billServices', 'ticket', 'user');
            if (auth()->user()->isDeptUser()) {
                $bills->where('created_by', Auth::user()->id);
            }
            $bills = $bills->orderBy('created_at', 'desc')->where('id', $id)->first();
            $all_payment = Payments::where('bill_id', $id)->get();
            $sum_payment_amount = Payments::where('bill_id', $id)->sum('amount');
            return view('admin.offlinepayment.onchange_data', ['bill' => $bills, 'all_payments' => $all_payment, 'payment_amount' => $sum_payment_amount, 'success' => "Payment Successfully Done"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
