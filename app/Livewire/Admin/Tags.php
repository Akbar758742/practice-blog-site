<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Tag;
use Illuminate\Support\Str;

class Tags extends Component
{
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

        Tag::create([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        $this->dispatch('swal:success', ['message' => 'Tag Created Successfully']);
        $this->resetInputFields();
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

        $tag = Tag::find($this->tagId);
        $tag->update([
            'name' => $this->name,
            'slug' => $this->slug,
        ]);

        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Tag Updated Successfully']);
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Tag::find($id)->delete();
        $this->dispatch('showToast', ['type' => 'success', 'message' => 'Tag Deleted Successfully']);
    }
}
