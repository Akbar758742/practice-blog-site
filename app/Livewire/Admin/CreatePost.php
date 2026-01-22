<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Str;

class CreatePost extends Component
{
    use WithFileUploads;

    public $title;
    public $slug;
    public $category_id;
    public $content;
    public $featured_image;
    public $is_published = 0;
    public $selectedTags = [];

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:posts,slug',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'featured_image' => 'nullable|image|max:2048', // 2MB Max
            'selectedTags' => 'array',
        ]);

        $imagePath = null;
        if ($this->featured_image) {
            $imageName = time() . '.' . $this->featured_image->extension();
            $this->featured_image->storeAs('images/posts', $imageName, 'public');
            $imagePath = $imageName;
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'category_id' => $this->category_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'featured_image' => $imagePath,
            'is_published' => $this->is_published,
            'published_at' => $this->is_published ? now() : null,
        ]);

        if (!empty($this->selectedTags)) {
            $post->tags()->sync($this->selectedTags);
        }

        $this->dispatch('swal:success', ['message' => 'Post Created Successfully']);
        return redirect()->route('admin.posts.index');
    }

    public function render()
    {
        return view('livewire.admin.create-post', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ])->layout('backend.layout.pages-layout', ['pageTitle' => 'Create Post']);
    }
}
