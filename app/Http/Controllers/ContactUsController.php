<?php

namespace App\Http\Controllers;

use App\ContactUs;
use App\Mail\Enquirymail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Setting;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\Mail;
class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = ContactUs::orderBy('id','desc')->get();
        return view('contact.index',compact('contacts'));
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
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ]);

        if(count($request->all()) > 0){
            unset($request['_token']);
            // $contactId = ContactUs::insertGetId($request->except(['_token']));
              $contact = ContactUs::create($request->all());

            if($contact->email !== null){
                // $contact = ContactUs::find($contactId);
                $this->enquiry($contact->email, $contact->id);
                Session::flash('message', "Thanks for contact us. we'll contact you later.");
                Session::flash('alert-class', 'alert-success');

            }
            return redirect()->route('thank-you');
        }
        Session::flash('message', "Something went wrong");
        Session::flash('alert-class', 'alert-danger');
        return redirect()->route('home');
    }
    public function enquiry($email, $id)
    {
        $data = ContactUs::where('id',$id)->first();
        try{
            $maildata = [
                'name'=>$data->name,
                'subject'=>$data->subject,
                'message'=>$data->message
            ];
            $setting = Setting::first();
            if($setting){
                $ccEmails = explode(',', $setting->company_email);
                $bccEmails = explode(',', $setting->company_alternative_email);
                Mail::to($email)->cc($ccEmails)->bcc($bccEmails)->send(new Enquirymail($maildata));
            }else{
            Mail::to($email)->send(new Enquirymail($maildata));
            }

        }catch(Throwable $t){
            Log::error('Mail sending failed: ' . $t->getMessage());
            throw $t;
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
        $contact = ContactUs::find($id);
        return view('contact.show',compact('contact'));
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
