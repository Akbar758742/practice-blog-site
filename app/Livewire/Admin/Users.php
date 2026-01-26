<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Role;
use App\Traits\AlertTrait;
use App\Traits\ActivityLogTrait;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination, AlertTrait, ActivityLogTrait;

    public $name, $email, $password, $user_id, $role_id;
    public $isOpen = false;
    public $search = '';

    // For delete confirmation
    public $confirmingUserDeletion = false;
    public $userToDelete = null;
    public $userToDeleteName = '';
    public $userToDeletePostCount = 0;
    public $userToDeleteCommentCount = 0;

    public function render()
    {
        $users = User::with('roles')
            ->withCount(['posts', 'comments'])
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
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

        try {
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

            // Log user creation
            $this->logCreated('user', $user, $user->name);

            $this->resetInputFields();
            $this->dispatch('close-modal');
            $this->successAlert('Success', 'User created successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while creating the user.');
        }
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

        try {
            $user = User::findOrFail($this->user_id);

            // Store old values for logging
            $oldValues = [
                'name' => $user->name,
                'email' => $user->email,
            ];

            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }

            $user->roles()->sync([$this->role_id]);

            // Log user update
            $this->logUpdated('user', $user, $oldValues, $user->name);

            $this->resetInputFields();
            $this->dispatch('close-modal');
            $this->successAlert('Success', 'User updated successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while updating the user.');
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);

            // Prevent self-deletion
            if ($user->id === auth()->id()) {
                $this->errorAlert('Error', 'You cannot delete your own account!');
                return;
            }

            // Check for associated content
            $postCount = $user->posts()->count();
            $commentCount = $user->comments()->count();

            // Always show confirmation modal
            $this->userToDelete = $id;
            $this->userToDeleteName = $user->name;
            $this->userToDeletePostCount = $postCount;
            $this->userToDeleteCommentCount = $commentCount;
            $this->confirmingUserDeletion = true;
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while deleting the user.');
        }
    }

    public function confirmDelete()
    {
        try {
            $user = User::findOrFail($this->userToDelete);

            // Prevent self-deletion
            if ($user->id === auth()->id()) {
                $this->errorAlert('Error', 'You cannot delete your own account!');
                $this->cancelDelete();
                return;
            }

            // Log user deletion with extra info
            $this->logDeleted('user', $user, $user->name, [
                'post_count' => $user->posts()->count(),
                'comment_count' => $user->comments()->count(),
                'roles' => $user->roles->pluck('name')->toArray(),
            ]);

            $user->delete();
            $this->cancelDelete();
            $this->resetPage();
            $this->successAlert('Deleted', 'User and all associated content deleted successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while deleting the user.');
            $this->cancelDelete();
        }
    }

    public function cancelDelete()
    {
        $this->confirmingUserDeletion = false;
        $this->userToDelete = null;
        $this->userToDeleteName = '';
        $this->userToDeletePostCount = 0;
        $this->userToDeleteCommentCount = 0;
    }
}
