<?php

namespace App\Livewire\Admin;

use App\Models\Permission;
use Livewire\Component;

class Permissions extends Component
{
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

        Permission::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'group_name' => $this->group_name,
        ]);

        $this->mount(); // Refresh list
        $this->dispatch('close-modal'); // Assuming you have JS to handle this or use dispatchBrowserEvent
        $this->resetFields();
        // Emit toast
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

        $permission = Permission::findOrFail($this->permission_id);
        $permission->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'group_name' => $this->group_name,
        ]);

        $this->mount();
        $this->dispatch('close-modal');
        $this->resetFields();
    }

    public function deletePermission($id)
    {
        Permission::findOrFail($id)->delete();
        $this->mount();
    }
}
