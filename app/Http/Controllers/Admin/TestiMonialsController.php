<?php

namespace App\Http\Controllers\Admin;

use App\TestiMonials;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class TestiMonialsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $testimonials  = TestiMonials::all();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'testimonial' => 'required',
            'company_name' => 'required',
            'designation' => 'required',
            'image' => 'required',
            'status' => 'required',
        ]);

        if($request->file('image')){
            $file= $request->file('image');
            $filename= str_replace(" ","_",date('YmdHi').$file->getClientOriginalName());
            $file-> move(public_path('images'), $filename);
        }

        $testimonials = TestiMonials::insert([
            'name' => $request->input('name'),
            'testimonial' => $request->input('testimonial'),
            'company_name' => $request->input('company_name'),
            'designation' => $request->input('designation'),
            'image' => $filename ?? '',
            'status' =>  $request->input('status') ?? ''
        ]);
        if($testimonials){
            Session::flash('message', 'Your Testimonial is created Successfully');
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', 'Something is wrong to create testimonials');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.testimonials.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\TestiMonials $testimonials
     * @return Application|Factory|View
     */
    public function show(TestiMonials $testimonials,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $testimonials = TestiMonials::find($id);
        return view('admin.testimonials.show', compact('testimonials'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\TestiMonials $testimonials
     * @return Application|Factory|View
     */
    public function edit(TestiMonials $testimonials,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $testimonials = TestiMonials::find($id);
        return view('admin.testimonials.edit',compact('testimonials'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\TestiMonials $testimonials
     * @return RedirectResponse
     */
    public function update(Request $request, TestiMonials $testimonials,$id)
    {
        $request->validate([
            'name' => 'required',
            'testimonial' => 'required',
            'company_name' => 'required',
            'designation' => 'required',
            'status' => 'required',
        ]);

        if($request->file('image')){
            $file= $request->file('image');
            $filename= str_replace(" ","_",date('YmdHi').$file->getClientOriginalName());
            $file-> move(public_path('images'), $filename);
        }

        $testimonials = TestiMonials::where('id',$id)->first();
        $testimonials->name = $request->input('name');
        $testimonials->testimonial = $request->input('testimonial');
        $testimonials->company_name = $request->input('company_name');
        $testimonials->designation = $request->input('designation');
        $testimonials->status = $request->input('status');
        if($request->file('image') != null){
            $testimonials->image = $filename;
        }
        $testimonials = $testimonials->save();
        if($testimonials){
            Session::flash('message', 'Your Testimonial is updated Successfully');
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', 'Something is wrong to update testimonials');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.testimonials.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\TestiMonials $testimonials
     * @return RedirectResponse
     */
    public function destroy(TestiMonials $testimonials,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $testimonials = TestiMonials::find($id)->delete();

        return  redirect()->route('admin.testimonials.index');
    }
}
