<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Partners;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class PartnersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $partners = Partners::all();
        return view('admin.partners.index',compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.partners.create');
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
            'company_name' => 'required',
            'company_logo' => 'required',
            'status' => 'required',
        ]);

        if($request->file('company_logo')){
            $file= $request->file('company_logo');
            $filename= str_replace(" ","_",date('YmdHi').$file->getClientOriginalName());
            $file-> move(public_path('img'), $filename);
        }

        $partner = Partners::insert([
            'company_name' => $request->input('company_name'),
            'company_logo' => $filename ?? '',
            'status' =>  $request->input('status') ?? ''
        ]);
        if($partner){
            Session::flash('message', 'Your partner data is created Successfully');
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', 'Something is wrong to create partner data');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.partners.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Partners  $partners
     * @return Application|Factory|View
     */
    public function show(Partners $partners,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $partner = Partners::find($id);
        return view('admin.partners.show',compact('partner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Partners  $partners
     * @return \Illuminate\Http\Response
     */
    public function edit(Partners $partners,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $partner = Partners::find($id);
        return view('admin.partners.edit',compact('partner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Partners  $partners
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partners $partners,$id)
    {
        $request->validate([
            'company_name' => 'required',
            'status' => 'required',
        ]);

        if($request->file('company_logo')){
            $file= $request->file('company_logo');
            $filename= str_replace(" ","_",date('YmdHi').$file->getClientOriginalName());
            $file-> move(public_path('img'), $filename);
        }

        $partner = Partners::where('id',$id)->first();
        $partner->company_name = $request->input('company_name');
        $partner->status = $request->input('status');
        if($request->file('company_logo') != null){
            $partner->company_logo = $filename;
        }
        $partner = $partner->save();
        if($partner){
            Session::flash('message', 'Your partner data is updated Successfully');
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', 'Something is wrong to update partner data');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.partners.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Partners  $partners
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Partners $partners,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Partners::find($id)->delete();

        return  redirect()->route('admin.partners.index');
    }
}
