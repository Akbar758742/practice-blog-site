<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Tag;
use App\Traits\AlertTrait;
use Illuminate\Support\Str;

class Tags extends Component
{
    use AlertTrait;

    public $tags;
    public $name;
    public $slug;
    public $tagId;
    public $isUpdateMode = false;
    public $deleteId = null;

    public function render()
    {
        $this->tags = Tag::orderBy('id', 'DESC')->get();
        return view('livewire.admin.tags')->layout('backend.layout.pages-layout', ['pageTitle' => 'Tags']);
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->name);
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->slug = '';
        $this->tagId = null;
        $this->isUpdateMode = false;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:tags,slug',
        ]);

        try {
            Tag::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);

            $this->successAlert('Success', 'Tag created successfully!');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while creating tag.');
        }
    }

    public function edit($id)
    {
        $tag = Tag::findOrFail($id);
        $this->name = $tag->name;
        $this->slug = $tag->slug;
        $this->tagId = $id;
        $this->isUpdateMode = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'slug' => 'required|unique:tags,slug,' . $this->tagId,
        ]);

        try {
            $tag = Tag::find($this->tagId);
            $tag->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);

            $this->successAlert('Success', 'Tag updated successfully!');
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while updating tag.');
        }
    }

    public function delete($id)
    {
        try {
            $tag = Tag::find($id);
            if ($tag) {
                $this->deleteId = $id;
                $postCount = $tag->posts()->count();
                $message = "<strong>Delete Tag: \"{$tag->name}\"?</strong><br><small class='text-muted'>Associated with {$postCount} post(s).<br>This action cannot be undone.</small>";
                $this->dispatch('swal:confirm-delete', [
                    'title' => 'Delete Tag',
                    'message' => $message,
                    'confirmCallback' => 'confirmDeleteTag'
                ]);
            }
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong.');
        }
    }

    public function confirmDeleteTag()
    {
        try {
            if (!$this->deleteId) return;
            
            Tag::find($this->deleteId)->delete();
            $this->deleteId = null;
            $this->successAlert('Deleted', 'Tag deleted successfully!');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while deleting tag.');
            $this->deleteId = null;
        }
    }
}
