<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\WhyChooseUs;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class WhyChooseUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $whyChooseUs = WhyChooseUs::all();
        return view('admin.whyChooseUs.index',compact('whyChooseUs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.whyChooseUs.create');
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
            'title' => 'required',
            'description' => 'required',
            'icon' => 'required',
            'status' => 'required',
        ]);

        if($request->file('icon')){
            $file= $request->file('icon');
            $filename= str_replace(" ","_",date('YmdHi').$file->getClientOriginalName());
            $file-> move(public_path('img'), $filename);
        }

        $partner = WhyChooseUs::insert([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'icon' => $filename ?? '',
            'status' =>  $request->input('status') ?? ''
        ]);
        if($partner){
            Session::flash('message', 'Your why choose us data is created Successfully');
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', 'Something is wrong to create why choose us data');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.whyChooseUs.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WhyChooseUs  $whyChooseUs
     * @return Application|Factory|View
     */
    public function show(WhyChooseUs $whyChooseUs,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $whyChooseUs = WhyChooseUs::find($id);
        return view('admin.whyChooseUs.show',compact('whyChooseUs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WhyChooseUs  $whyChooseUs
     * @return Application|Factory|View
     */
    public function edit(WhyChooseUs $whyChooseUs,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $whyChooseUs = WhyChooseUs::find($id);
        return view('admin.whyChooseUs.edit',compact('whyChooseUs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WhyChooseUs  $whyChooseUs
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, WhyChooseUs $whyChooseUs,$id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if($request->file('icon')){
            $file= $request->file('icon');
            $filename= str_replace(" ","_",date('YmdHi').$file->getClientOriginalName());
            $file-> move(public_path('img'), $filename);
        }

        $whyChooseUs = WhyChooseUs::where('id',$id)->first();
        $whyChooseUs->title = $request->input('title');
        $whyChooseUs->description = $request->input('description');
        $whyChooseUs->status = $request->input('status');
        if($request->file('icon') != null){
            $whyChooseUs->icon = $filename;
        }
        $whyChooseUs = $whyChooseUs->save();
        if($whyChooseUs){
            Session::flash('message', 'Your why choose us data is updated Successfully');
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', 'Something is wrong to update why choose us data');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.whyChooseUs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WhyChooseUs  $whyChooseUs
     * @return \Illuminate\Http\Response
     */
    public function destroy(WhyChooseUs $whyChooseUs,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        WhyChooseUs::find($id)->delete();

        return  redirect()->route('admin.whyChooseUs.index');
    }
}
