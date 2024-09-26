<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Otp;
use App\RoleUser;
use App\User;
use Illuminate\Support\Facades\Hash;

class OtpController extends Controller
{
    public function check_otp(Request $request)
    {
        $value = session('email');

        $otpDigits = $request->input('otp', []);
        $otp = implode('', $otpDigits);

        // return $request->otp;
        $otp = Otp::where('otp', $otp)->where('email', $value)->first();


        if($otp){

                $request->request->add([
                    'currency' => $request->input('country') == '103' ? 'INR' : 'USD',
                    'origin_type' => $request->input('country') == '103' ? 'Domestic' : 'International'
                ]);

                $data = $request->toArray();
                 $user = User::create([
                     'title' => session('title'),
                     'name' => session('name'),
                     'email' => session('email'),
                     'password' =>session('password'),
                     'decrypt_password' => session('password'),
                    'number' => session('number'),
                    'country_id' => session('country'),
                     'currency' => session('currency'),
                     'origin_type' => session('origin_type'),
                     'city' => session('city'),
                     'company' => session('company'),
                     'designation' => session('designation'),
                     'member_id' => session('icc_member_id'),
                     'is_gst' => session('isGST') ? 1 : 0,
                     'gst_no' => session('isGST') && session('isGST') == '1' && !empty(session('gst_no')) ? session('gst_no') : null,
                     'billing_address' => session('billing_address'),
                 ]);
                 if ($user) {
                        RoleUser::create([
                             'user_id' => $user->id,
                             'role_id' => config('constant.user_role_id')
                         ]);

                         $setting = config('app.setting');
                         if (!empty($setting)) {
                             $cc = $bcc = null;
                             $cc = explode(',', $setting['email_cc']);
                             $bcc = explode(',', $setting['email_bcc']);
                         }
                         $option = [
                            'user' => $user->name,
                             'supportEmail' => isset($setting) && !empty($setting) ? $setting['email_cc'] : '',
                             'user_email' => $user->email,
                            'user_password' => $user->decrypt_password
                         ];
                     }
                    return redirect()->route('login');
        }

    }

    public function get_otp()
    {
        return view('otp.otp');
    }

    }

