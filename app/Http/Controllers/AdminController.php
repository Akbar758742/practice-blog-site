<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminController extends Controller
{
    public function AdminDashboard()
    {
        return view('backend.pages.dashboard');
    }

    public function logoutHandler(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('fail', 'You have been logged out');
    }


    public function profileView(Request $request)
    {
        $data = [
            // 'user'=>Auth::user(),
            'pageTitle' => 'Admin Profile'
        ];
        return view('backend.pages.profile', $data);
    }

    public function profilePicUpdate(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $path = 'images/users/';
        $file = $request->file('profilePicturefile');
        $old_picture = $user->getAttributes()['picture'];
        $file_path = public_path($path . $old_picture);

        if ($request->hasFile('profilePicturefile')) {
            if ($old_picture != null && \File::exists($file_path)) {
                \File::delete($file_path);
            }
            $new_picture_name = 'UIMG' . date('Ymd') . uniqid() . '.jpg';
            $upload = $file->move(public_path($path), $new_picture_name);

            if ($upload) {
                $user->update([
                    'picture' => $new_picture_name
                ]);
                return response()->json(['status' => 1, 'message' => 'Profile picture updated successfully']);
            } else {
                return response()->json(['status' => 0, 'message' => 'Something went wrong']);
            }
        }
        return response()->json(['status' => 0, 'message' => 'No file selected']);
    }
}
