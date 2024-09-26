<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pages;
use Dompdf\FrameReflower\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Pages::all();
        return view('admin.pages.index',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.create');
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
            'page_name' => 'required',
            'page_title' => 'required',
            'page_content' => 'required',
        ]);
        $page_name = $request->input('page_name');
        $title = $request->input('page_title');
        $content = $request->input('page_content');
        $data = [
            'page_name' => $page_name,
            'title' => $title,
            'content' => $content
        ];
        $pages = Pages::insert($data);
        if($pages){
            Session::flash('message', "Pages data are inserted successfully.");
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', "Something is wrong to insert pages data.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Pages::where('id',$id)->first();
        return view('admin.pages.show',compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Pages::where('id',$id)->first();
        return view('admin.pages.edit',compact('page'));
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
        $request->validate([
            'page_title' => 'required',
            'page_content' => 'required',
        ]);
        $title = $request->input('page_title');
        $content = $request->input('page_content');
        $data = [
            'title' => $title,
            'content' => $content
        ];
        $pages = Pages::where('id',$id)->update($data);
        if($pages){
            Session::flash('message', "Pages data are updated successfully.");
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', "Something is wrong to update pages data.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.pages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $pages = Pages::find($id)->delete();
        return  redirect()->route('admin.pages.index');
    }
}
