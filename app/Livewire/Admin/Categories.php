<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use App\Traits\AlertTrait;
use Illuminate\Support\Str;

class Categories extends Component
{
    use AlertTrait;

    public $categories;
    public $name;
    public $slug;
    public $parent_id;
    public $categoryId;
    public $isUpdateMode = false;
    public $deleteId = null;

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

        try {
            Category::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'parent_id' => $this->parent_id ?: null,
            ]);

            $this->successAlert('Success', 'Category created successfully!');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while creating category.');
        }
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

        try {
            $category = Category::find($this->categoryId);
            $category->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'parent_id' => $this->parent_id ?: null,
            ]);

            $this->successAlert('Success', 'Category updated successfully!');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while updating category.');
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::find($id);
            if ($category) {
                $this->deleteId = $id;
                $postCount = $category->posts()->count();
                $message = "<strong>Delete Category: \"{$category->name}\"?</strong><br><small class='text-muted'>Associated with {$postCount} post(s).<br>This action cannot be undone.</small>";
                $this->dispatch('swal:confirm-delete', [
                    'title' => 'Delete Category',
                    'message' => $message,
                    'confirmCallback' => 'confirmDeleteCategory'
                ]);
            }
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong.');
        }
    }

    public function confirmDeleteCategory()
    {
        try {
            if (!$this->deleteId) return;
            
            Category::find($this->deleteId)->delete();
            $this->deleteId = null;
            $this->successAlert('Deleted', 'Category deleted successfully!');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while deleting category.');
            $this->deleteId = null;
        }
    }
}
