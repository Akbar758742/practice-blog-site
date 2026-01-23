<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    public $name, $email, $password, $user_id, $role_id;
    public $isOpen = false;
    public $search = '';

    public function render()
    {
        $users = User::with('roles')
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.admin.users', [
            'users' => $users,
            'roles' => Role::all(),
        ])->layout('backend.layout.pages-layout');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role_id = null;
        $this->user_id = null;
        $this->isOpen = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'username' => \Str::slug($this->name) . '-' . rand(1000, 9999),
            'status' => \App\UserStatus::ACTIVE, // Set default status
        ]);

        // Ensure role_id is cast to integer and attach
        if ($this->role_id) {
            $user->roles()->attach((int) $this->role_id);
        }

        $this->resetInputFields();
        $this->dispatch('close-modal');
        $this->dispatch('swal:success', ['message' => 'User created successfully!']);
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->roles->first()?->id;
        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'role_id' => 'required',
        ]);

        $user = User::findOrFail($this->user_id);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        if ($this->password) {
            $user->update(['password' => Hash::make($this->password)]);
        }

        $user->roles()->sync([$this->role_id]);

        $this->resetInputFields();
        $this->dispatch('close-modal');
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
    }
}
