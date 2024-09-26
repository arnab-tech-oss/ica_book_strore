<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $setting = Setting::first();
        return view('setting',compact('setting'));
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
        if($request->get('id') == null)
        {
            $setting = new Setting();
        }else{
            $setting = Setting::find($request->get('id'));
        }
        $fileName= '';
        if($request->hasFile('company_logo')) {
            $logoFile = $request->file('company_logo');
            $fileName = Storage::disk('setting')->put('setting-logo', $logoFile);
            $setting->company_logo = $fileName;
        }
        $setting->software_title = $request->get('software_title');
        $setting->software_description = $request->get('software_description');
        $setting->software_version = $request->get('software_version');
        $setting->company_name = $request->get('company_name');
        $setting->company_logo = $fileName;
        $setting->company_intro = $request->get('company_intro');
        $setting->company_email = $request->get('company_email');
        $setting->company_alternative_email = $request->get('company_alternative_email');
        $setting->company_contact_no = $request->get('company_contact_no');
        $setting->company_alternative_contact_no = $request->get('company_alternative_contact_no');
        $setting->company_gst_no = $request->get('company_gst_no');
        $setting->billing_header = $request->get('billing_header');
        $setting->billing_footer = $request->get('billing_footer');
        $setting->email_cc = $request->get('email_cc');
        $setting->email_bcc = $request->get('email_bcc');
        $setting->account_email = $request->get('account_email');
        $setting->api_key = $request->get('api_key');
        $setting->save();

        if($setting){
            Session::flash('message', "Settings data are updated successfully.");
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', "Something is wrong to update settings data.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.setting.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
