<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;

class Categories extends Component
{
    public $categories;
    public $name;
    public $slug;
    public $parent_id;
    public $categoryId;
    public $isUpdateMode = false;

    public function render()
    {
        $this->categories = Category::with('parent')->get();
        return view('livewire.admin.categories', [
            'parents' => Category::whereNull('parent_id')->get()
        ])->layout('backend.layout.pages-layout', ['pageTitle' => 'Categories']);
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->parent_id = null;
        $this->categoryId = null;
        $this->isUpdateMode = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
        ]);

        Category::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id ?: null,
        ]);

        $this->dispatch('swal:success', ['message' => 'Category Created Successfully']);
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->parent_id = $category->parent_id;
        $this->categoryId = $id;
        $this->isUpdateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $this->categoryId,
        ]);

        $category = Category::find($this->categoryId);
        $category->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'parent_id' => $this->parent_id ?: null,
        ]);

        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Category Updated Successfully']);
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Category::find($id)->delete();
        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Category Deleted Successfully']);
    }
}
