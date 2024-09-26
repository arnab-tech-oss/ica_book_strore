<?php

namespace App\Http\Controllers\Admin;

use App\EmailLog;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $allData = EmailLog::select('email_logs.id as emailId','email_logs.date as emailDate','users.id as userId','users.*','email_logs.*')->join('users','users.id','email_logs.user_id')->orderBy('email_logs.date','desc')->get();
        return view('admin.emailLog.index',compact('allData'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmailLog  $emailLog
     * @return \Illuminate\Http\Response
     */
    public function show(EmailLog $emailLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmailLog  $emailLog
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailLog $emailLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmailLog  $emailLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmailLog $emailLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailLog  $emailLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailLog $emailLog)
    {
        //
    }
}
