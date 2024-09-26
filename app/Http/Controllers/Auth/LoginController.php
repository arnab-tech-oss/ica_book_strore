<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use http\Client\Curl\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        // $this->validateLogin($request);
        $request->validate([
            'email' => "required|email"
        ]);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        $email = $request->input('email');
        if ($email) {
            $user = \App\User::where('email', $request->input('email'))->first();
        }
        if ((isset($user) && $user->status != '0')) {
            if ($user != '') {
                // added the email address into the session for password check in to the function login_with_password()
                session(['user_email' => $user->email]);
                return redirect()->route('login.with.password');
            }
        }else{
            return redirect()->route('register_form');
        }
        if ((isset($user) && $user->status == '0')) {
            Session::flush();
            Session::flash('message', "Your account access is revoked, please contact to administrator");
            Session::flash('alert-class', 'alert-danger');
            return redirect()->route('login');
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }



    public function login_with_password(Request $request)
    {
        if (!empty($request->input())) {

            if ($request->email == session('user_email')) {

                $attempt = $this->attemptLogin($request);
                if ($request->hasSession()) {
                    $request->session()->put('auth.password_confirmed_at', time());

                }
                $user = \App\User::where('email', $request->email)->first();

                if ($attempt && $user != '') {
                    Auth::login($user);
                    if(auth()->user()->isAdmin()){
                        return redirect()->route('admin.home');
                    }else{
                        return redirect()->route('admin.user');

                    }
                } else {
                    return back()->with('password', 'Pasword not matched with our records');
                }
            } else {
                return $this->sendFailedLoginResponse($request);
            }
        } else {
            return view('auth.login_password');
        }
    }
}
