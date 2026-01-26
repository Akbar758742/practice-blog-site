<?php

namespace App\Livewire\Admin;

use App\Models\Permission;
use App\Traits\AlertTrait;
use App\Traits\ActivityLogTrait;
use Livewire\Component;

class Permissions extends Component
{
    use AlertTrait, ActivityLogTrait;

    public $permissions;
    public $name, $slug, $group_name, $permission_id;
    public $isEdit = false;
    public $deleteId = null;

    public function mount()
    {
        $this->permissions = Permission::all();
    }

    public function render()
    {
        return view('livewire.admin.permissions')->layout('backend.layout.pages-layout');
    }

    public function resetFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->group_name = '';
        $this->permission_id = null;
        $this->isEdit = false;
    }

    public function createPermission()
    {
        $this->resetFields();
    }

    public function storePermission()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:permissions,slug',
            'group_name' => 'required',
        ]);

        try {
            Permission::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'group_name' => $this->group_name,
            ]);

            // Log permission creation
            $permission = Permission::where('slug', $this->slug)->first();
            if ($permission) {
                $this->logCreated('permission', $permission, $permission->name);
            }

            $this->mount(); // Refresh list
            $this->dispatch('close-modal');
            $this->resetFields();
            $this->successAlert('Success', 'Permission created successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while creating the permission.');
        }
    }

    public function editPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $this->name = $permission->name;
        $this->slug = $permission->slug;
        $this->group_name = $permission->group_name;
        $this->permission_id = $permission->id;
        $this->isEdit = true;
    }

    public function updatePermission()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:permissions,slug,' . $this->permission_id,
            'group_name' => 'required',
        ]);

        try {
            $permission = Permission::findOrFail($this->permission_id);

            // Store old values for logging
            $oldValues = [
                'name' => $permission->name,
                'slug' => $permission->slug,
                'group_name' => $permission->group_name,
            ];

            $permission->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'group_name' => $this->group_name,
            ]);

            // Log permission update
            $this->logUpdated('permission', $permission, $oldValues, $permission->name);

            $this->mount();
            $this->dispatch('close-modal');
            $this->resetFields();
            $this->successAlert('Success', 'Permission updated successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while updating the permission.');
        }
    }

    public function deletePermission($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $this->deleteId = $id;
            $roleCount = $permission->roles()->count();
            $message = "<strong>Delete Permission: \"{$permission->name}\"?</strong><br><small class='text-muted'>Used by {$roleCount} role(s).<br>This action cannot be undone.</small>";
            $this->dispatch('swal:confirm-delete', [
                'title' => 'Delete Permission',
                'message' => $message,
                'confirmCallback' => 'confirmDeletePermission'
            ]);
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong.');
        }
    }

    public function confirmDeletePermission()
    {
        try {
            if (!$this->deleteId) {
                $this->errorAlert('Error', 'No permission selected for deletion.');
                return;
            }

            $permission = Permission::findOrFail($this->deleteId);
            $this->logDeleted('permission', $permission, $permission->name, [
                'roles' => $permission->roles->pluck('name')->toArray(),
            ]);
            $permission->delete(); // Use soft delete
            $this->deleteId = null;
            $this->mount();
            $this->successAlert('Deleted', 'Permission deleted successfully!');
        } catch (\Exception $e) {
            $this->deleteId = null;
            $this->errorAlert('Error', 'Could not delete permission. Please try again.');
        }
    }
}
