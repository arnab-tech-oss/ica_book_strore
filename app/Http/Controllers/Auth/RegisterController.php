<?php

namespace App\Http\Controllers\Auth;

use App\Countries;
use App\Http\Controllers\Controller;
use App\Mail\UserRegistrationMail;
use App\RoleUser;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $countries = Countries::all();
        return view('auth.register', compact('countries'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'number' => ['required', 'numeric'],
            // 'country' => ['required', 'numeric'],
            'city' => ['required', 'string'],
            'company' => ['required', 'string'],
            'designation' => ['required', 'string'],
        ]);
    }

    public function register(Request $request)
    {
        // $this->validator($request->all())->validate();

        $request->request->add([
            'currency' => $request->input('country') == '103' ? 'INR' : 'USD',
            'origin_type' => $request->input('country') == '103' ? 'Domestic' : 'International'
        ]);
        event(new Registered($user = $this->create($request->all())));

        if ($user) {
            RoleUser::create([
                'user_id' => $user->id,
                'role_id' => config('constant.user_role_id')
            ]);
        }
        $this->guard()->login($user);
        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'title' => $data['title'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'decrypt_password' => $data['password'],
            'number' => $data['number'],
            'country_id' => $data['country'],
            'currency' => $data['currency'],
            'origin_type' => $data['origin_type'],
            'city' => $data['city'],
            'company' => $data['company'],
            'designation' => $data['designation'],
            'member_id' => $data['icc_member_id'],
            'is_gst' => isset($data['isGST']) ? 1 : 0,
            'gst_no' => isset($data['isGST']) && $data['isGST'] == '1' && !empty($data['gst_no']) ? $data['gst_no'] : null,
            'billing_address' => $data['billing_address'],
        ]);

        $cc = "";
        $bcc = "";
        Mail::to($data['email'])->send(new UserRegistrationMail($data, $cc, $bcc));

        return $user;
    }
}
