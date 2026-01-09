<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Traits\AlertTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Livewire\Admin\TopUserInfo;
use Illuminate\Support\Facades\Session;
use App\Helpers\Cmail;


class Profile extends Component
{
    use AlertTrait;

    public $tab = null;
    public $tabname = 'personal_details';
    protected $queryString = ['tab' => ['keep' => true]];

    public $name, $username, $email, $bio, $picture, $address, $phone, $gender;
    public $current_password, $new_password, $new_password_confirmation;
    public function selectTab($tab)
    {
        $this->tab = $tab;
    }
    public function mount(Request $request)
    {
        $this->tab = $request->get('tab') ? $request->get('tab') : $this->tabname;
        $user = User::findOrFail(auth()->user()->id);
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->bio = $user->bio;
        $this->picture = $user->picture;

    }


    public function updateProfile()
    {
        $user = User::findOrFail(auth()->user()->id);

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',

        ]);

        try {
            $user->name = $this->name;
            $user->email = $this->email;
            $user->bio = $this->bio;
            // $user->picture=$this->picture;

            $updated = $user->save();
            sleep(0.5);

            if ($updated) {
                $this->successAlert('Success', 'Profile updated successfully.');
                $this->dispatch('updateTopUserInfo')->to(TopUserInfo::class);
            }
        } catch (\Exception $e) {
            //  dd($e->getMessage());
            $this->errorAlert('Error', 'Failed to update profile. Please try again.');
        }
    }

    public function updatePassword()
    {

        $user = User::findOrFail(auth()->user()->id);

        $this->validate([
            'current_password' => [
                'required',
                'min:5',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        return $fail('The current password is incorrect.');
                    }
                },
            ],
            // 'new_password' => 'required|min:5|confirmed',

               'new_password'=>  [
                    'required',

                    'min:5',
                    'confirmed',
                    function($attribute, $value, $fail) use ($user) {
                        if (Hash::check($value, $user->password)) {
                            $fail('The new password must be different from the current password.');
                        }
                    },
                ]


        ]);

        $updated = $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        if ($updated) {
            $data = array(
                'user' => $user,
                'new_password' => $this->new_password,
            );
            $email_body = view('email-templates.password-change-template',$data)->render();
            $mail_config = array(
                'recipient_address' => $user->email,
                'recipient_name' => $user->name,
                'subject' => 'Password Changed',
               'body'=>$email_body
            );
            Cmail::send($mail_config);
            auth()->logout();
            $this->successAlert('Success', 'Password updated successfully.');
            $this->current_password = '';
            $this->new_password = '';
            $this->new_password_confirmation = '';
            $this->redirectRoute('admin.login');
        } else {
            $this->errorAlert('Error', 'Failed to update password. Please try again.');

        }
    }


    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => User::findOrFail(auth()->user()->id)
        ]);
    }
}
