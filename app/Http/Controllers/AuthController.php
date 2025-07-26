<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\UserStatus;
use App\UserType;
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

    public function loginHandler(Request $request)

    {

        $fieldType=filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

         if($fieldType=='email')
         {
            $request->validate([
                'login_id' => 'required|email|exists:users,email',
                'password' => 'required|min:5|max:20',
            ],
            [
                'login_id.required' => 'Email or Username is required',
                'login_id.exists' => 'Email or Username is not found',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 5 characters',
                'password.max' => 'Password must be less than 20 characters',
            ]
            );
         }

         else{
            $request->validate([
                'login_id' => 'required|exists:users,username',
                'password' => 'required|min:5|max:20',
            ],
            [
                'login_id.required' => 'Email or Username is required',
                'login_id.exists' => 'Email or Username is not found',
                'password.required' => 'Password is required',
                'password.min' => 'Password must be at least 5 characters',
                'password.max' => 'Password must be less than 20 characters',
            ]
            );
         }

        //  if(Auth::attempt([$fieldType => $request->login_id,'password'=>$request->password,'status'=>UserStatus::ACTIVE]))
        //  {
        //     return redirect()->route('admin.dashboard');
        //  }
        //  else{
        //     return redirect()->back()->with('error','Invalid login details');

        //  }

    }
}
