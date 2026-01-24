<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Traits\AlertTrait;

class Posts extends Component
{
    use WithPagination, AlertTrait;

    public $search;

    public function render()
    {
        $query = Post::with(['category', 'user'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            });

        // RBAC: Author can only see their own posts, Admin/Editor sees all
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('editor')) {
            $query->where('user_id', auth()->id());
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.posts', ['posts' => $posts])
            ->layout('backend.layout.pages-layout', ['pageTitle' => 'All Posts']);
    }

    public function delete($id)
    {
        try {
            $post = Post::find($id);
            $this->authorize('delete', $post);
            if ($post) {
                // Delete image if exists
                if ($post->featured_image && \File::exists(public_path('storage/images/posts/' . $post->featured_image))) {
                    \File::delete(public_path('storage/images/posts/' . $post->featured_image));
                }
                $post->delete();
                $this->successAlert('Deleted', 'Post deleted successfully!');
            }
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while deleting post.');
        }
    }
}
