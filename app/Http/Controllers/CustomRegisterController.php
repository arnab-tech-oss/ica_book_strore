<?php

namespace App\Http\Controllers;

use App\Countries;
use App\Http\Controllers\Auth\RegisterController;
use App\Mail\UserRegistrationMail;
use App\RoleUser;
use App\TicketsTags;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;
use App\Mail\OtpMail;
use App\Otp;

class CustomRegisterController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'number' => ['required', 'numeric'],
            'country' => ['required', 'numeric'],
            'city' => ['required', 'string'],
            // 'company' => ['required', 'string'],
            // 'designation' => ['required', 'string'],
        ]);

        $request->request->add([
            'currency' => $request->input('country') == '103' ? 'INR' : 'USD',
            'origin_type' => $request->input('country') == '103' ? 'Domestic' : 'International'
        ]);

        $data = $request->toArray();
        // $user = User::create([
        //     'title' => $data['title'],
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        //     'decrypt_password' => $data['password'],
        //     'number' => $data['number'],
        //     'country_id' => $data['country'],
        //     'currency' => $data['currency'],
        //     'origin_type' => $data['origin_type'],
        //     'city' => $data['city'],
        //     'company' => $data['company'],
        //     'designation' => $data['designation'],
        //     'member_id' => $data['icc_member_id'],
        //     'is_gst' => isset($data['isGST']) ? 1 : 0,
        //     'gst_no' => isset($data['isGST']) && $data['isGST'] == '1' && !empty($data['gst_no']) ? $data['gst_no'] : null,
        //     'billing_address' => $data['billing_address'],
        // ]);

        session([
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

        $otp = rand(11111,44444);

        if($request->email !== null){
            $this->otp_mail($request->email, $otp);
          }

          Otp::create([
            'email' => $request->email,
            'otp' => $otp
          ]);

        // if ($user) {
        //     RoleUser::create([
        //         'user_id' => $user->id,
        //         'role_id' => config('constant.user_role_id')
        //     ]);

        //     $setting = config('app.setting');
        //     if (!empty($setting)) {
        //         $cc = $bcc = null;
        //         $cc = explode(',', $setting['email_cc']);
        //         $bcc = explode(',', $setting['email_bcc']);
        //     }
        //     $option = [
        //         'user' => $user->name,
        //         'supportEmail' => isset($setting) && !empty($setting) ? $setting['email_cc'] : '',
        //         'user_email' => $user->email,
        //         'user_password' => $user->decrypt_password
        //     ];
        //     if($user->email !== null){
        //          $this->otp_mail($user->email , $user->id);
        //        }
        // }
        return redirect()->route('otp.get');
    }

    public function showForm()
    {
        $countries = Countries::all();
        return view('auth.register', compact('countries'));
    }

    public function otp_mail($email, $otp)
    {
        // $data = User::where('id',$id)->first();

        try{
            $maildata = [
                // 'name' => $data->name,
                // 'phone' => $data->phone,
                'app_name' => "Ica Trade Desk",
                'otp' => $otp
            ];

            Mail::to($email)->send(new OtpMail($maildata));
        } catch (Throwable $t) {
            Log::error('Mail sending failed: ' . $t->getMessage());
            throw $t;
        }

    }
}
