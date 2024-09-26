<?php

namespace App\Http\Controllers\Admin;

use App\Bill;
use App\Http\Requests\MassDestroyTicketRequest;
use App\Link;
use App\Service;
use App\Ticket;
use App\User;
use Gate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class LinksController extends Controller
{

    const FULLY_PAID = 'fully_paid';
    const PARTIALLY_PAID = 'partially_paid';
    const NOT_PAID = 'not_paid';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Link::with(['tickets'])
                ->whereHas('tickets',function ($query){
                    if(auth()->user()->isDeptUser()){
                        $query->where('assigned_to_user_id',Auth::user()->id);
                    }
                })
                ->orderBy('id','desc')
                ->select(sprintf('%s.*', (new Link)->table))->get();
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'ticket_show';
                $editGate = 'ticket_edit';
                $deleteGate = 'ticket_delete';
                $crudRoutePart = 'tickets';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : "";
            });
            $table->editColumn('title', function ($row) {
                return $row->ticket_id ? $row->title : "";
            });
            $table->addColumn('status_name', function ($row) {
                return $row->status ? $row->status->name : '';
            });

            $table->addColumn('view_link', function ($row) {
                return route('admin.links.show', $row->id);
            });

            return $table->make(true);
        }

        $links = Link::with('tickets.user')
            ->whereHas('tickets',function ($query){
                if(auth()->user()->isDeptUser()){
                    $query->where('assigned_to_user_id',Auth::user()->id);
                }
            })->orderBy('id', 'desc')->get();
        return view('admin.links.index', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bills = Bill::with('billServices','ticket','user');
                    if(auth()->user()->isDeptUser()){
                        $bills->where('created_by',Auth::user()->id);
                    }
        $bills = $bills->orderBy('created_at', 'desc')->get();
        return view('admin.links.create', compact('bills'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'bill_id' => 'required',
            'cost' => 'required'
        ]);
        abort_if(Gate::denies('link_generate_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $bill_id = $request->bill_id;
        $bill = Bill::find($bill_id);
        $user = User::find($bill->created_by);
        $ticket = Ticket::find($bill->ticket_id);
        $remarks = $request->remarks;
        $cost = ($request->cost * 100);
       // $cost = number_format( $cost, 2);
        $api = new Api(config('constant.razorpay_key'),config('constant.razorpay_secret'));
        $currency = $user->currency != null ? $user->currency : 'INR';
        $callback = config('constant.base_url').'callback_url';
        // $callback = 'http://127.0.0.1:8000/callback_url';
        $url = $api->paymentLink->create([
                'amount' => (int)$cost,
                'currency' => $currency,
                'description' => $ticket->title ?? '' .' Service',
                'customer' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'contact' => $user->number
                ],
                'notify' => [
                    'sms' => true,
                    'email' => true
                ],
                'reminder_enable' => true,
                'notes' => [
                    'ticket_id' => $ticket->id,
                    'ticket_name' => $ticket->title,
                    'bill_id' => $bill_id,
                    'cost' => $cost
                ],
                'callback_url' => $callback,
                'callback_method' => 'get'
        ]);
        if($url){
            $bill = Bill::where('id',$bill_id)->first();
            $remaining_cost = (int)$bill->bill_cost - $request->cost;
            $links = Link::insert([
                'ticket_id' => $ticket->id,
                'bill_id' => $bill_id,
                'payment_url' => $url->short_url,
                'cost' => $request->cost,
                'remarks' => $remarks,
                'status' => $url->status,
                'payment_link_id' => $url->id,
                'payment_link_json' => json_encode($url->toArray()),
                'payment_status' => self::NOT_PAID
            ]);
        }

        return redirect()->route('admin.links.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('link_generate_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $links = Link::with('tickets.user')->where('id',$id)->first();
        return view('admin.links.show', compact('links'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Link::where('id', $id)->delete();

        return redirect()->back();
    }

    public function massDestroy(MassDestroyTicketRequest $request)
    {
        Link::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
