<?php

namespace App\Livewire\Admin;

use App\Models\Permission;
use App\Traits\AlertTrait;
use Livewire\Component;

class Permissions extends Component
{
    use AlertTrait;

    public $permissions;
    public $name, $slug, $group_name, $permission_id;
    public $isEdit = false;

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
            $permission->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'group_name' => $this->group_name,
            ]);

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
            Permission::findOrFail($id)->delete();
            $this->mount();
            $this->successAlert('Deleted', 'Permission deleted successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while deleting the permission.');
        }
    }
}
