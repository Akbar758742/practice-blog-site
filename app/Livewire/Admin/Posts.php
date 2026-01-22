<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

class Posts extends Component
{
    use WithPagination;

    public $search;

    public function render()
    {
        $posts = Post::with(['category', 'user'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.posts', ['posts' => $posts])
            ->layout('backend.layout.pages-layout', ['pageTitle' => 'All Posts']);
    }

    public function delete($id)
    {
        $post = Post::find($id);
        if ($post) {
            // Delete image if exists
            if ($post->featured_image && \File::exists(public_path('storage/images/posts/' . $post->featured_image))) {
                \File::delete(public_path('storage/images/posts/' . $post->featured_image));
            }
            $post->delete();
            $this->dispatch('swal:success', ['message' => 'Post Deleted Successfully']);
        }
    }
}
