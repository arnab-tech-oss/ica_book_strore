<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCommentRequest;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Ticket;
use App\User;
use Gate;
use Illuminate\Support\Facades\Log;
use App\Mail\CommandsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CommentsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('comment_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $comments = Comment::orderBy('created_at','desc')->get();

        return view('admin.comments.index', compact('comments'));
    }

    public function create()
    {
        abort_if(Gate::denies('comment_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tickets = Ticket::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::where("status",'1')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.comments.create', compact('tickets', 'users'));
    }

    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create($request->all());
        // if($comment){
            if($comment->author_email !== null){
               $this->commands_add($comment->author_email , $comment->id);
            // }
            Session::flash('message', "Comment data is inserted successfully.");
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', "Something is wrong to insert comment data.");
            Session::flash('alert-class', 'alert-danger');
        }

        return redirect()->route('admin.comments.index');
    }

    public function edit(Comment $comment)
    {
        abort_if(Gate::denies('comment_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tickets = Ticket::all()->pluck('title', 'id')->prepend(trans('global.pleaseSelect'), '');

        $users = User::where("status",'1')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $comment->load('ticket', 'user');

        return view('admin.comments.edit', compact('tickets', 'users', 'comment'));
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());
        if($comment){
            Session::flash('message', "Comment data is updated successfully.");
            Session::flash('alert-class', 'alert-success');
        }else{
            Session::flash('message', "Something is wrong to update comment data.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.comments.index');
    }

    public function show(Comment $comment)
    {
        abort_if(Gate::denies('comment_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $comment->load('ticket', 'user');

        return view('admin.comments.show', compact('comment'));
    }

    public function destroy(Comment $comment)
    {
        abort_if(Gate::denies('comment_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $comment->delete();

        return back();
    }

    public function massDestroy(MassDestroyCommentRequest $request)
    {
        Comment::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function commands_add($email,$id){
        $data = Comment::where('id',$id)->first();
        try{
            $maildata = [
                // 'name' => $data->name,
                // 'phone' => $data->phone,
                'app_name' => "Ica Trade Desk",
            ];

            Mail::to($email)->send(new CommandsMail($maildata));
        } catch (Throwable $t) {
            Log::error('Mail sending failed: ' . $t->getMessage());
            throw $t;
        }
}
}
