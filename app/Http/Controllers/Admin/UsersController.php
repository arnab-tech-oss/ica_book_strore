<?php

namespace App\Http\Controllers\Admin;

use App\Countries;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\PasswordChangeMail;
use App\Mail\UserStatusUpdatedMail;
use App\Role;
use App\Ticket;
use App\TicketsTags;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;


class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $users = User::whereHas('roles', function ($query) {
        //     $query->where('role_id','=', config('constant.user_role_id'));
        // })
        //     ->get();
        $users = User::orderBy('id', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $countries = Countries::all();
        return view('admin.users.create', compact('roles', 'countries'));
    }

    public function store(StoreUserRequest $request)
    {
        if ($request->input('password')) {
            $request->request->add(['decrypt_password' => $request->input('password')]);
        }
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));
        if ($user) {
            Session::flash('message', "User is created successfully.");
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', "Something is wrong to create user.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $countries = Countries::all();
        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user', 'countries'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $password = $request->input('password');
        $setting = config('app.setting');
        if (!empty($setting)) {
            $cc = $bcc = null;
            $cc = explode(',', $setting['email_cc']);
            $bcc = explode(',', $setting['email_bcc']);
        }
        if ($request->input('password')) {
            $request->request->add(['decrypt_password' => $request->input('password')]);
            $option = [
                'user' => $user->name,
                'supportEmail' => isset($setting) && !empty($setting) ? $setting['email_cc'] : '',
                'user_email' => $user->email,
                'user_password' => $password
            ];
            try {
                Mail::to($user->email)->send(new PasswordChangeMail($option,$cc,$bcc));
            }catch(\Exception $e){
                Log::info($e->getMessage());
            }
        }
        if ($user->status != $request->input('status')) {
            $status = $request->input('status') == 1 ? 'Activated' : 'Deactivated';
            $option = [
                'user' => $user->name,
                'supportEmail' => isset($setting) && !empty($setting) ? $setting['email_cc'] : '',
                'status' => $status,
            ];
            try {
                Mail::to($user->email)->send(new UserStatusUpdatedMail($option,$cc,$bcc));
            }catch(\Exception $e){
                Log::info($e->getMessage());
            }
        }
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
        if ($user) {
            Session::flash('message', "User is updated successfully.");
            Session::flash('alert-class', 'alert-success');
        } else {
            Session::flash('message', "Something is wrong to update user.");
            Session::flash('alert-class', 'alert-danger');
        }
        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $id = $user->id;
        $checkUser = $this->checkUserTicketOrPaymentExists($id);
        if ($checkUser > 0) {
            Session::flash('message', "User is not deleted because of child records.");
            Session::flash('alert-class', 'alert-danger');
            return back();
        }
        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $id = request('ids');
        $checkUser = $this->checkUserTicketOrPaymentExists($id);
        if ($checkUser > 0) {
            Session::flash('message', "User is not deleted because of child records.");
            Session::flash('alert-class', 'alert-danger');
            return response(null, Response::HTTP_NO_CONTENT);
        }
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function checkUserTicketOrPaymentExists($ids)
    {
        $ids = is_int($ids) ? [$ids] : $ids;
        if (!empty($ids)) {
            return Ticket::whereIn('created_by', $ids)->count();
        }
        return 0;
    }

    public function customerList()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('role_id', config('constant.user_role_id'));
        })
            ->get();
        return view('admin.customer.index', compact('users'));
    }

    public function customerShow($id)
    {
        if($id){
            $user = User::with(['roles','country'])->where('id',$id)->first();
            return view('admin.customer.show', compact('user'));
        }
        return redirect()->back();
    }

    public function showallcustomer(){
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }


}
