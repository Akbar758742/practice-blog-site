<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Page;
use App\Traits\AlertTrait;
use App\Traits\ActivityLogTrait;
use Illuminate\Support\Str;

class EditPage extends Component
{
    use AlertTrait, ActivityLogTrait;

    public $page_id;
    public $title;
    public $slug;
    public $content;
    public $is_visible;
    public $meta_title;
    public $meta_desc;

    public function mount($id)
    {
        $page = Page::findOrFail($id);
        $this->page_id = $page->id;
        $this->title = $page->title;
        $this->slug = $page->slug;
        $this->content = $page->content;
        $this->is_visible = $page->is_visible;
        $this->meta_title = $page->meta_title;
        $this->meta_desc = $page->meta_desc;
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function update()
    {
        if (!auth()->user()->hasRole('admin')) {
            $this->errorAlert('Error', 'Unauthorized.');
            return;
        }

        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:pages,slug,' . $this->page_id,
            'content' => 'nullable',
        ]);

        $page = Page::findOrFail($this->page_id);

        // Store old values for logging
        $oldValues = [
            'title' => $page->title,
            'slug' => $page->slug,
            'is_visible' => $page->is_visible,
        ];

        $page->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'is_visible' => $this->is_visible,
            'meta_title' => $this->meta_title,
            'meta_desc' => $this->meta_desc,
        ]);

        // Log page update
        $this->logUpdated('page', $page, $oldValues, $page->title);

        $this->successAlert('Success', 'Page updated successfully!');
        return redirect()->route('admin.pages.index');
    }

    public function render()
    {
        return view('livewire.admin.edit-page')
            ->layout('backend.layout.pages-layout', ['pageTitle' => 'Edit Page']);
    }
}
