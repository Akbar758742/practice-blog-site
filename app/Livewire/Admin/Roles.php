<?php

namespace App\Livewire\Admin;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;

class Roles extends Component
{
    public $roles;
    public $name, $slug, $role_id;
    public $selectedPermissions = [];
    public $isEdit = false;

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

        $role = Role::create([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        $role->permissions()->sync($this->selectedPermissions);

        $this->mount();
        $this->dispatch('close-modal');
        $this->resetFields();
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

        $role = Role::findOrFail($this->role_id);
        $role->update([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        $role->permissions()->sync($this->selectedPermissions);

        $this->mount();
        $this->dispatch('close-modal');
        $this->resetFields();
    }

    public function deleteRole($id)
    {
        Role::findOrFail($id)->delete();
        $this->mount();
    }
}
