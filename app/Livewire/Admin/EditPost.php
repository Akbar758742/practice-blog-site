<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Traits\AlertTrait;
use App\Traits\ActivityLogTrait;
use Illuminate\Support\Str;

class EditPost extends Component
{
    use WithFileUploads, AlertTrait, ActivityLogTrait;

    public $postId;
    public $title;
    public $slug;
    public $category_id;
    public $content;
    public $featured_image;
    public $old_featured_image;
    public $status;
    public $comments_allowed;
    public $selectedTags = [];

    public function mount($id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);

        $this->postId = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->category_id = $post->category_id;
        $this->content = $post->content;
        $this->old_featured_image = $post->featured_image;
        $this->status = $post->status->value;
        $this->comments_allowed = $post->comments_allowed;
        $this->selectedTags = $post->tags->pluck('id')->toArray();
    }

    public function generateSlug()
    {
        $this->slug = Str::slug($this->title);
    }

    public function update()
    {
        $post = Post::find($this->postId);
        $this->authorize('update', $post);

        // Store old values for logging
        $oldValues = [
            'title' => $post->title,
            'slug' => $post->slug,
            'category_id' => $post->category_id,
            'status' => $post->status->value ?? $post->status,
            'content' => $post->content,
        ];


        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|unique:posts,slug,' . $this->postId,
            'category_id' => 'required|exists:categories,id',
            'content' => 'required',
            'featured_image' => 'nullable|image|max:2048', // 2MB Max
            'selectedTags' => 'array',
        ]);

        $imagePath = $this->old_featured_image;
        if ($this->featured_image) {
            $imageName = time() . '.' . $this->featured_image->extension();
            $this->featured_image->storeAs('images/posts', $imageName, 'public');
            $imagePath = $imageName;

            if ($this->old_featured_image && \File::exists(public_path('storage/images/posts/' . $this->old_featured_image))) {
                \File::delete(public_path('storage/images/posts/' . $this->old_featured_image));
            }
        }

        // Check Publish Permission logic
        if ($this->status === 'published' && $post->status->value !== 'published') {
            $this->authorize('publish', $post);
        }

        $post->update([
            'category_id' => $this->category_id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'featured_image' => $imagePath,
            'status' => $this->status,
            'published_at' => ($this->status === 'published' && !$post->published_at) ? now() : $post->published_at,
            'comments_allowed' => $this->comments_allowed,
        ]);

        if (!empty($this->selectedTags)) {
            $post->tags()->sync($this->selectedTags);
        } else {
            $post->tags()->detach();
        }

        // Log post update with changes
        $this->logUpdated('post', $post, $oldValues, $post->title);

        $this->successAlert('Success', 'Post updated successfully!');
        return redirect()->route('admin.posts.index');
    }

    public function render()
    {
        return view('livewire.admin.edit-post', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ])->layout('backend.layout.pages-layout', ['pageTitle' => 'Edit Post']);
    }
}
