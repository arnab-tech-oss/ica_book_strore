<?php

namespace App\Http\Controllers\Admin;

use App\CaseStudies;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class CaseStudiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $caseStudies  = CaseStudies::all();
        return view('admin.caseStudies.index', compact('caseStudies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('admin.caseStudies.create');
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
            'title' => 'required',
            'description' => 'required',
            'banner_image' => 'required',
            'status' => 'required',
        ]);

        if($request->file('banner_image')){
            $file= $request->file('banner_image');
            $filename= str_replace(" ","_",date('YmdHi').$file->getClientOriginalName());
            $file-> move(public_path('images'), $filename);
        }

        $case_study = CaseStudies::insert([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'banner_image' => $filename ?? '',
            'status' =>  $request->input('status') ?? ''
        ]);
        if($case_study){
            Session::flash('message', 'Your Case Study is created Successfully');
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', 'Something is wrong to create case study');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.caseStudies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CaseStudies  $caseStudies
     * @return \Illuminate\Http\Response
     */
    public function show(CaseStudies $caseStudies,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $caseStudies = CaseStudies::find($id);
        return view('admin.caseStudies.show', compact('caseStudies'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CaseStudies  $caseStudies
     * @return \Illuminate\Http\Response
     */
    public function edit(CaseStudies $caseStudies,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $caseStudies = CaseStudies::find($id);
        return view('admin.caseStudies.edit', compact('caseStudies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CaseStudies  $caseStudies
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CaseStudies $caseStudies,$id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        if($request->file('banner_image')){
            $file= $request->file('banner_image');
            $filename= str_replace(" ","_",date('YmdHi').$file->getClientOriginalName());
            $file-> move(public_path('images'), $filename);
        }

        $case_study = CaseStudies::where('id',$id)->first();
        $case_study->title = $request->input('title');
        $case_study->description = $request->input('description');
        $case_study->status = $request->input('status');
        if($request->file('banner_image') != null){
            $case_study->banner_image = $filename;
        }
        $case_study = $case_study->save();
        if($case_study){
            Session::flash('message', 'Your case study is updated Successfully');
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', 'Something is wrong to update case study');
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.caseStudies.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CaseStudies  $caseStudies
     * @return \Illuminate\Http\Response
     */
    public function destroy(CaseStudies $caseStudies,$id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        CaseStudies::find($id)->delete();
        return  redirect()->route('admin.caseStudies.index');
    }
}
