<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\Permission;
use App\Traits\AlertTrait;
use App\Traits\ActivityLogTrait;
use Livewire\Component;

class Roles extends Component
{
    use AlertTrait, ActivityLogTrait;

    public $roles;
    public $name, $slug, $role_id;
    public $selectedPermissions = [];
    public $isEdit = false;
    public $deleteId = null;

    public function mount()
    {
        $this->roles = Role::with('permissions')->get();
    }

    public function render()
    {
        return view('livewire.admin.roles', [
            'permissions' => Permission::all()->groupBy('group_name')
        ])->layout('backend.layout.pages-layout');
    }

    public function resetFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->role_id = null;
        $this->selectedPermissions = [];
        $this->isEdit = false;
    }

    public function createRole()
    {
        $this->resetFields();
    }

    public function storeRole()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:roles,slug',
        ]);

        try {
            $role = Role::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);

            $role->permissions()->sync($this->selectedPermissions);

            // Log role creation
            $this->logCreated('role', $role, $role->name);

            $this->mount();
            $this->dispatch('close-modal');
            $this->resetFields();
            $this->successAlert('Success', 'Role created successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while creating the role.');
        }
    }

    public function editRole($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->role_id = $role->id;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        $this->isEdit = true;
    }

    public function updateRole()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:roles,slug,' . $this->role_id,
        ]);

        try {
            $role = Role::findOrFail($this->role_id);

            // Store old values for logging
            $oldValues = [
                'name' => $role->name,
                'slug' => $role->slug,
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ];

            $role->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);

            $role->permissions()->sync($this->selectedPermissions);

            // Log role update
            $this->logUpdated('role', $role, $oldValues, $role->name);

            $this->mount();
            $this->dispatch('close-modal');
            $this->resetFields();
            $this->successAlert('Success', 'Role updated successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while updating the role.');
        }
    }

    public function deleteRole($id)
    {
        try {
            $role = Role::findOrFail($id);
            $this->deleteId = $id;
            $userCount = $role->users()->count();
            $message = "<strong>Delete Role: \"{$role->name}\"?</strong><br><small class='text-muted'>Assigned to {$userCount} user(s).<br>This action cannot be undone.</small>";
            $this->dispatch('swal:confirm-delete', [
                'title' => 'Delete Role',
                'message' => $message,
                'confirmCallback' => 'confirmDeleteRole'
            ]);
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong.');
        }
    }

    public function confirmDeleteRole()
    {
        try {
            if (!$this->deleteId) {
                $this->errorAlert('Error', 'No role selected for deletion.');
                return;
            }

            $role = Role::findOrFail($this->deleteId);
            $this->logDeleted('role', $role, $role->name, [
                'user_count' => $role->users()->count(),
                'permissions' => $role->permissions->pluck('name')->toArray(),
            ]);
            $role->delete(); // Use soft delete
            $this->deleteId = null;
            $this->mount();
            $this->successAlert('Deleted', 'Role deleted successfully!');
        } catch (\Exception $e) {
            $this->deleteId = null;
            $this->errorAlert('Error', 'Could not delete role. Please try again.');
        }
    }
}
