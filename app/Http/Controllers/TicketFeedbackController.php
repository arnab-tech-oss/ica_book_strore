<?php

namespace App\Http\Controllers;

use App\Status;
use App\Ticket;
use App\TicketFeedback;
use App\TicketFeedBackUrl;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TicketFeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$ticket_id)
    {
        $ticket_id = base64_decode($ticket_id);
        $ticket = Ticket::find($ticket_id);
        $user = User::find($ticket->created_by);
        $url = config('constant.base_url').'feedback/'.base64_encode($ticket_id);
        $ticket_url_id = TicketFeedBackUrl::where('ticket_id',$ticket_id)->where('user_id',$user->id)->where('url',$url)->orderby('id','desc')->first();
        if(isset($ticket_url_id) && $ticket_url_id->status == "closed"){
            Session::flash('message', "Your Feedback URL is expired.");
            Session::flash('alert-class', 'alert-danger');
            return view('feedback.index',compact('ticket'));
        }
        return view('feedback.index',compact('ticket'));
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'rating1' => 'required',
            'ticket_id' => 'required',
        ]);
        $ticket_id = $request->input('ticket_id');
        $rating = $request->input('rating1');
        $comment = $request->input('message');
        $url = config('constant.base_url').'feedback/'.base64_encode($ticket_id);
        $ticket = Ticket::find($ticket_id);
        $user = User::find($ticket->created_by);
        $ticket_url_id = TicketFeedBackUrl::where('ticket_id',$ticket_id)->where('user_id',$user->id)->where('url',$url)->first();
        $ticket_feedback_url_id = null;
        if($ticket_url_id != null){
            $ticket_feedback_url_id = $ticket_url_id->id;
            $ticket_url_id->status = 'closed';
            $ticket_url_id->save();
        }
        $ticket->update(['status_id' => Status::CLOSED_ID]);
        TicketFeedback::insert([
           'ticket_id' => $ticket_id,
           'ratings' => $rating,
           'comments' => $comment,
           'ticket_feedback_url_id' => $ticket_feedback_url_id,
        ]);
        Session::flash('message', "Your Feedback Received Successfully.");
        Session::flash('alert-class', 'alert-success');
        return redirect()->route('thank-you');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TicketFeedback  $ticketFeedback
     * @return \Illuminate\Http\Response
     */
    public function show(TicketFeedback $ticketFeedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TicketFeedback  $ticketFeedback
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketFeedback $ticketFeedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TicketFeedback  $ticketFeedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TicketFeedback $ticketFeedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TicketFeedback  $ticketFeedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketFeedback $ticketFeedback)
    {
        //
    }
}
