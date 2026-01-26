<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Page;
use App\Traits\AlertTrait;
use App\Traits\ActivityLogTrait;
use Illuminate\Support\Str;

class CreatePage extends Component
{
    use AlertTrait, ActivityLogTrait;

    public $title;
    public $slug;
    public $content;
    public $is_visible = true;
    public $meta_title;
    public $meta_desc;

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function store()
    {
        // Simple permission check or use Policy if needed.
        // Assuming only Admins can manage pages for now as per "Role-based editing" requirement on Step 4.
        if (!auth()->user()->hasRole('admin')) {
            $this->errorAlert('Error', 'Unauthorized.');
            return;
        }

        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:pages,slug',
            'content' => 'nullable',
        ]);

        Page::create([
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'is_visible' => $this->is_visible,
            'meta_title' => $this->meta_title,
            'meta_desc' => $this->meta_desc,
        ]);

        // Log page creation
        $page = Page::where('slug', $this->slug)->first();
        if ($page) {
            $this->logCreated('page', $page, $page->title);
        }

        $this->successAlert('Success', 'Page created successfully!');
        return redirect()->route('admin.pages.index');
    }

    public function render()
    {
        return view('livewire.admin.create-page')
            ->layout('backend.layout.pages-layout', ['pageTitle' => 'Create Page']);
    }
}
