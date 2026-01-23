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
            Tag::find($id)->delete();
            $this->successAlert('Deleted', 'Tag deleted successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while deleting tag.');
        }
    }
}
