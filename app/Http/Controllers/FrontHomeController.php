<?php

namespace App\Http\Controllers;

use App\CaseStudies;
use App\Category;
use App\Faq;
use App\Pages;
use App\Partners;
use App\Service;
use App\TestiMonials;
use App\User;
use App\WhyChooseUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class FrontHomeController extends Controller
{
    public function index(){
        $homePage = Pages::where('page_name','home_page')->first();
        $about = Pages::where('page_name','about_page')->first();
        $testimonialPage = Pages::where('page_name','testimonial_page')->first();
        $testimonials = TestiMonials::where('status','1')->get();
        $partners = Partners::where('status','1')->get();
        $caseStudies = CaseStudies::where('status','1')->get();
        $whyChooseUs = WhyChooseUs::where('status','1')->get();
        $faqs = Faq::where('status','1')->get();
        $serviceCategories = Category::all();
        $settings = null;
        if(!empty(config('app.setting'))){
            $settings = config('app.setting');
        }
        if(Auth::user() !== null && !empty(Auth::user()) && Auth::user()->isUser()){
            $user = Auth::user();
            $notIn = Service::where('parent_service_id', '!=', null)
                ->pluck('parent_service_id')->toArray();
            $services = Service::with('category')
                ->whereNotIn('id', $notIn)
                ->where('status', '1')
                ->where('currency', $user->currency)
                ->orderBy('category_id')
                ->get();
        }else{
            $currency = session('set_currency');
            $user = Auth::user();
            if(isset($user)){
                $currency = $user->currency;
            }else if(!isset($user) && !isset($currency)){
                $currency = 'INR';
            }
            $services = Service::with('category')
                            ->where('currency', $currency)
                            ->where('status','1')
                            ->orderBy('category_id')
                            ->get();
        }

        return view('front_home', compact('settings','homePage','testimonialPage','services','about','testimonials','serviceCategories','partners','caseStudies','whyChooseUs','faqs'));
    }

    public function setCurrency(Request $request){
        $value = $request->input('currency');
        if(isset($value)){
            session([ "set_currency" => $value ]);
            return true;
        }
        return false;
    }

    public function getProfile(){
        $user = \Illuminate\Support\Facades\Auth::user();
        if(isset($user)){
            $user = User::with(['country','roles'])->where('id',$user->id)->first();
            return view('profile',compact('user'));
        }else{
            return redirect()->route('login');
        }
    }

    public function profileStore(Request $request){
        $user = \Illuminate\Support\Facades\Auth::user();
        if(isset($user) && $user->email == $request->input('email')){
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'country' => 'required',
                'currency' => 'required',
            ]);

            $name = $request->input('name');
            $password = $request->input('password');
            $address = $request->input('address');
            if($user->password != $password){
                $password = Hash::make($password);
            }
            $data = User::where('id',$user->id)->update([
                'name' => $name,
                'password' => $password,
                'billing_address' => $address
            ]);
            if($data){
                Session::flash('message', "Your profile updated successfully.");
                Session::flash('alert-class', 'alert-success');
            }else{
                Session::flash('message', "Something went wrong to updated profile.");
                Session::flash('alert-class', 'alert-danger');
            }
            return redirect()->back();
        }
    }
}
