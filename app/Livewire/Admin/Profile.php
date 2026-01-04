<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Traits\AlertTrait;
use Illuminate\Http\Request;

class Profile extends Component
{
    use AlertTrait;

    public $tab=null;
    public $tabname='personal_details';
    protected $queryString=['tab'=>['keep' => true]];

    public $name, $username, $email, $bio, $picture,$address, $phone, $gender;
    public function selectTab($tab){
        $this->tab=$tab;
    }
      public function mount(Request $request){
        $this->tab=$request->get('tab')? $request->get('tab'):$this->tabname;
        $user=User::findOrFail(auth()->user()->id);
        $this->name=$user->name;
        $this->username=$user->username;
        $this->email=$user->email;
        $this->bio=$user->bio;
        $this->picture=$user->picture;

      }

      public function updateProfile(){
        $user=User::findOrFail(auth()->user()->id);

        $this->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users,email,'.$user->id,
            'bio'=>'nullable|string|max:500',

        ]);

        try {
            $user->name=$this->name;
            $user->email=$this->email;
            $user->bio=$this->bio;
            // $user->picture=$this->picture;

            $updated= $user->save();
            sleep(0.5);

            if($updated){
                $this->successAlert('Success', 'Profile updated successfully.');
                $this->dispatch('updateTopUserInfo')->to(TopUserInfo::class);
            }
        } catch(\Exception $e) {
            //  dd($e->getMessage());
            $this->errorAlert('Error', 'Failed to update profile. Please try again.');
        }
      }
    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => User::findOrFail(auth()->user()->id)
        ]);
    }
}
