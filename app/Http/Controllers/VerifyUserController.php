<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class VerifyUserController extends Controller
{
    public function verifyUser(Request $request){
        $userEmail = $request->input('email');
        return (User::where('email', $userEmail)->count() == 0 ? false : true);
    }
}
