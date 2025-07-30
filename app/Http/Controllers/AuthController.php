<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\UserStatus;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helpers\Cmail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function loginForm()
    {
        $data = [
            'title' => 'Login'

        ];
        return view('backend.pages.auth.login', compact('data'));
    }

    public function forgetPassword()
    {
        $data = [
            'title' => 'Forget Password'
        ];
        return view('backend.pages.auth.forgot', compact('data'));
    }

    public function loginHandler(Request $request)

    {

        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($fieldType == 'email') {
            $request->validate(
                [
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
        } else {
            $request->validate(
                [
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

        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password
        );

        if (Auth::attempt($creds)) {
            //check if account is inactive mode
            if (auth()->user()->status == UserStatus::INACTIVE) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('fail', 'Your account is currently inactive. Please contact support at (support@larablog.test) for more information.');
            }
            //check if account is pending
            if (auth()->user()->status == UserStatus::Pending) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('fail', 'Your account is currently pending. Please contact support at (support@larablog.test) for more information.');
            }
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('admin.login')->with('fail', 'Invalid login details');
        }
    }

    public function sendPasswordResetLink(Request $request)
    {

        $request->validate(
            [
                'email' => 'required|email|exists:users,email',
            ],
            [
                'email.required' => 'the email is required',
                'email.email' => 'the :attribute must be a valid email address',
                'email.exists' => 'we cannot find the user with this email address',
            ]
        );

        $user = User::where('email', $request->email)->first();
        $token = base64_encode(Str::random(64));
        //check if token already exists
        $oldToken = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($oldToken) {
            DB::table('password_reset_tokens')->where('email', $request->email)->update([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        } else {
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
        }


        $actionLink = route('admin.resetPasswordForm', ['token' => $token,]);
        $data = array(
            'user' => $user,
            'actionLink' => $actionLink
        );
        $mail_body = view('email-templates.forgot-template', $data)->render();
        $mailConfig = array(
            //  'from_address'=>config('mail.from.address'),
            //  'from_name'=>config('mail.from.name'),
            'recipient_address' => $user->email,
            'recipient_name' => $user->name,
            'subject' => 'Password Reset',
            'body' => $mail_body
        );

        if (Cmail::send($mailConfig)) {
            return redirect()->route('admin.forgetPassword')->with('success', 'Password reset link has been sent to your email');
        } else {
            return redirect()->route('admin.forgetPassword')->with('fail', 'Password reset link could not be sent to your email');
        }
    }

    public function resetPasswordForm(Request $request, $token = null)
    {
        $isTokenExists = DB::table('password_reset_tokens')->where('token', $token)->first();
        if (!$isTokenExists) {
            return redirect()->route('admin.forgetPassword')->with('fail', 'Invalid token');
        } else {

            $diffMins=Carbon::createFromFormat('Y-m-d H:i:s', $isTokenExists->created_at)->diffInMinutes(now());
            if($diffMins>5){
                DB::table('password_reset_tokens')->where('token', $token)->delete();
                return redirect()->route('admin.forgetPassword')->with('fail', 'the password reset link you clicked has expired. Please request a new password reset link');
            }
            $data = [
                'title' => 'Reset Password',
                'token' => $token
            ];
        }
        return view('backend.pages.auth.reset-password', $data);
    }

    public function resetPasswordHandler(Request $request)
    {
        $request->validate(
            [
                'new_password' => 'required|min:5|max:20|required_with:confirm_new_password|same:confirm_new_password',
                'confirm_new_password' => 'required',
            ],
            [
                'new_password.required' => 'the new password is required',
                'new_password.min' => 'the new password must be at least 5 characters',
                'new_password.max' => 'the new password must be less than 20 characters',
                'new_password.required_with' => 'the confirm new password is required',
                'new_password.same' => 'the new password and confirm new password must match',
                'confirm_new_password.required' => 'the confirm new password is required',
                'confirm_new_password.same' => 'the new password and confirm new password must match',
            ]

        );

        // $token = $request->token;
        // $isTokenExists = DB::table('password_reset_tokens')->where('token', $token)->first();
        // if (!$isTokenExists) {
        //     return redirect()->route('admin.forgetPassword')->with('fail', 'Invalid token');
        // } else {
        //     $user = User::where('email', $isTokenExists->email)->first();
        //     $user->password = Hash::make($request->password);
        //     $user->save();
        //     DB::table('password_reset_tokens')->where('token', $token)->delete();
        //     return redirect()->route('admin.login')->with('success', 'Password has been reset successfully');
        // }


      $dbToken= DB::table('password_reset_tokens')->where('token', $request->token)->first();
      $user = User::where('email', $dbToken->email)->first();
      User::where('email',$user->email)->update(['password'=>Hash::make($request->new_password)]);


      //send notification email to this user email address that contains new password

      $data = array(
          'user' => $user,
         'new_password' => $request->new_password
      );
      $mail_body = view('email-templates.password-change-template', $data)->render();
      $mailConfig = array(
          //  'from_address'=>config('mail.from.address'),
          //  'from_name'=>config('mail.from.name'),
          'recipient_address' => $user->email,
          'recipient_name' => $user->name,
          'subject' => 'Password changes',
          'body' => $mail_body
      );

      if (Cmail::send($mailConfig)) {
        DB::table('password_reset_tokens')->where(['email'=>$dbToken->email,'token'=>$request->token])->delete();
          return redirect()->route('admin.login')->with('success', 'done! your password has been changed. Now you can login with your new password');
      } else {
          return redirect()->route('admin.resetPasswordForm',['token'=>$dbToken->token])->with('fail', 'something went wrong. Please try again');
      }

      return redirect()->route('admin.login')->with('success', 'Password has been reset successfully');


    }
}
