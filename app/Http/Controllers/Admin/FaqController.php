<?php

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $faqs = Faq::all();
        return view('admin.faq.index',compact('faqs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.faq.create');
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
            'question' => 'required',
            'answer'   => 'required',
            'status'   => 'required'
        ]);

        $faq = Faq::create($request->all());
        if($faq){
            Session::flash('message', "Your Faq Question is created successfully.");
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', "Something is wrong to create Faq Question.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.faq.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Faq  $faq
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        abort_if(\Illuminate\Support\Facades\Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $faqs = Faq::find($id);
        return view('admin.faq.show', compact('faqs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Faq  $faq
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $faqs = Faq::find($id);
        return view('admin.faq.edit',compact('faqs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Faq  $faq
     * @return RedirectResponse
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'question' => 'required',
            'answer'   => 'required',
            'status'   => 'required'
        ]);

        $faq = Faq::find($id);
        $faq->question = $request->input('question');
        $faq->answer = $request->input('answer');
        $faq->status = $request->input('status');
        $faq = $faq->save();
        if($faq){
            Session::flash('message', "Your Faq Question is updated successfully.");
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', "Something is wrong to update Faq Question.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.faq.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faq  $faq
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('cms_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $faqs = Faq::find($id)->delete();

        return  redirect()->route('admin.faq.index');
    }
}
