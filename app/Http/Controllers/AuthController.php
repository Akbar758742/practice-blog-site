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

         $creds=array(
            $fieldType => $request->login_id,
            'password' => $request->password
         );

            if(Auth::attempt($creds)){
                //check if account is inactive mode
                if(auth()->user()->status==UserStatus::INACTIVE){
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('admin.login')->with('fail','Your account is currently inactive. Please contact support at (support@larablog.test) for more information.');
                }
                //check if account is pending
                if(auth()->user()->status==UserStatus::Pending){
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('admin.login')->with('fail','Your account is currently pending. Please contact support at (support@larablog.test) for more information.');
                }
                return redirect()->route('admin.dashboard');
            }


         else{
            return redirect()->route('admin.login')->with('fail','Invalid login details');

         }

    }

    public function sendPasswordResetLink(Request $request)
    {
        dd($request->all());
    //     $request->validate([
    //         'email' => 'required|email|exists:users,email',
    //     ]);

    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return $status === Password::RESET_LINK_SENT
    //                 ? back()->with(['status' => __($status)])
    //                 : back()->withErrors([
    //                     'email' => __($status),
    //                 ]);
    // }
}
}
