<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginForm()
    {
         $data=[
             'title'=>'Login'

         ];
        return view('backend.pages.auth.login',compact('data'));
    }

    public function forgetPassword()
    {
        $data=[
            'title'=>'Forget Password'
        ];
        return view('backend.pages.auth.forgot',compact('data'));
    }
}
