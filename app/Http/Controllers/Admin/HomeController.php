<?php

namespace App\Http\Controllers\Admin;

use App\Payments;
use App\Service;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Ticket;
use WpOrg\Requests\Auth;

class HomeController
{
    public function index()
    {
        abort_if(Gate::denies('dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $WIPTickets = Ticket::where('status_id','3')->count();
        $openTickets = Ticket::whereHas('status', function ($query) {
            $query->whereName('Open');
        })->count();
        $closedTickets = Ticket::whereHas('status', function ($query) {
            $query->whereName('Closed');
        })->count();
        $totalPayments = Payments::where('payment_link_status', 'paid')->sum('amount');

        $supportRequest = Ticket::orderBy('created_at', 'desc')->take(3)->get();
        $payments = Payments::orderBy('created_at', 'desc')->take(3)->get();
        $services = Service::with('category')->where('status','1')->orderBy('category_id');
        $user = \Auth::user();
        $services->where('currency',$user->currency);
        $services = $services->get();

        return view('home', compact('WIPTickets', 'openTickets', 'closedTickets', 'totalPayments', 'supportRequest', 'payments','services'));
    }

    public function user()
    {
        abort_if(Gate::denies('user_dashboard_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $WIPTickets = Ticket::where('status_id','3')->where('created_by', \Auth::user()->id)->count();

        $openTickets = Ticket::where('created_by', \Auth::user()->id)->whereHas('status', function ($query) {
            $query->whereName('Open');
        })->count();

        $closedTickets = Ticket::where('created_by', \Auth::user()->id)->whereHas('status', function ($query) {
            $query->whereName('Closed');
        })->count();

        $totalPayments = Payments::with(['bill.ticket'])->whereHas('bill.ticket', function ($user) {
            $user->where('created_by', \Auth::user()->id);
        })->where('payment_link_status', 'paid')->sum('amount');

        $supportRequest = Ticket::where('created_by', \Auth::user()->id)->orderBy('created_at', 'desc')->take(3)->get();;
        $payments = Payments::with(['bill.ticket','bill.invoices'])->whereHas('bill.ticket', function ($user) {
            $user->where('created_by', \Auth::user()->id);
        })->orderBy('id', 'desc')->take(3)->get();;

        $user = \Illuminate\Support\Facades\Auth::user();
        $notIn = Service::where('parent_service_id', '!=', null)
            ->pluck('parent_service_id')->toArray();
        $serviceCards = Service::whereNotIn('id', $notIn)
            ->where('status', '1')
            ->where('currency', $user->currency)
            ->get();
        return view('home', compact('WIPTickets', 'openTickets', 'closedTickets', 'totalPayments', 'supportRequest', 'payments','serviceCards'));
    }

    public function adminSearch(Request $request){
        $search = $request->input('search');
        $users = User::with(['userTickets.bills.payments','comments','roles' => function($query){
            $query->where('id',config('constant.user_role_id') ?? '2');
        }])->where('name','LIKE','%'.$search.'%')->get();
        if(count($users) > 0){
            $html = "";
            foreach ($users as $key => $user){
                $tickets = $user->userTickets ?? null;
                $comments = $user->comments ?? null;
                $bills = $payments = [];
                $noRecords = 0;
                $html .= '<ul class="list-group pb-2">';
                    $html .= '<li class="list-group-item bg-secondary text-white text-capitalize">'. $user->name .'</li>';
                    if(count($tickets) > 0){
                        $html .= '<li class="list-group-item bg-primary text-capitalize">User '. trans("cruds.ticket.title") .'</li>';
                        foreach ($tickets as $tKey => $ticket){
                            $html .= '<a href="'. route('admin.tickets.show', $ticket->id) .'"><li class="list-group-item list-group-item-action">'. $ticket->title .'</li></a>';
                            $bills = $ticket->bills ?? [];
                            if(count($bills) > 0){
                                $html .= '<li class="list-group-item bg-primary text-capitalize">User '.trans("cruds.bills.title").'</li>';
                                foreach ($bills as $bKey => $bill){
                                    $html .= '<a href="'. route('admin.bills.show', $bill->id) .'"><li class="list-group-item list-group-item-action">'. $bill->bill_cost .'</li></a>';
                                    $payments = $bill->payments ?? [];
                                    if(count($payments) > 0){
                                        $html .= '<li class="list-group-item bg-primary text-capitalize">User '.trans("cruds.links.title_singular").'</li>';
                                        foreach ($payments as $tKey => $payment){
                                            $html .= '<a href="'. route('admin.payment.show', $payment->id) .'"><li class="list-group-item list-group-item-action">'. $payment->payment_id .'</li></a>';
                                        }
                                    }
                                }
                            }
                        }
                        $noRecords++;
                    }
                    if(count($comments) > 0){
                        $html .= '<li class="list-group-item bg-primary text-capitalize">User '.trans("cruds.comment.title").'</li>';
                        foreach ($comments as $tKey => $comment){
                            $html .= '<a href="'. route('admin.comments.show', $comment->id) .'"><li class="list-group-item list-group-item-action">'. $comment->comment_text .'</li><a/>';
                        }
                        $noRecords++;
                    }
                    if($noRecords == 0){
                        $html .= '<li class="list-group-item list-group-item-action">No User Records Found.</li>';
                    }
                $html .= '</ul>';
            }
            return [
                'status' => 'success',
                'msg' => 'User Found',
                'data' => $html
            ];
        }else{
            return [
                'status' => 'failed',
                'msg' => 'No User Found',
                'data' => []
            ];
        }
    }
}
