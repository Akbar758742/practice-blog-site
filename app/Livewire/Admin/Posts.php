<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use App\Traits\AlertTrait;
use App\Traits\ActivityLogTrait;

class Posts extends Component
{
    use WithPagination, AlertTrait, ActivityLogTrait;

    public $search;
    public $deleteId = null;
    public $showTrashed = false;

    public function render()
    {
        $query = Post::with(['category', 'user'])
            ->withCount('comments')
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%');
            });

        // Show trashed or active posts
        if ($this->showTrashed) {
            $query->onlyTrashed();
        }

        // RBAC: Author can only see their own posts, Admin/Editor sees all
        if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('editor')) {
            $query->where('user_id', auth()->id());
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get trash count for badge
        $trashCount = Post::onlyTrashed()->count();

        return view('livewire.admin.posts', [
            'posts' => $posts,
            'trashCount' => $trashCount
        ])->layout('backend.layout.pages-layout', ['pageTitle' => $this->showTrashed ? 'Trashed Posts' : 'All Posts']);
    }

    public function toggleTrashed()
    {
        $this->showTrashed = !$this->showTrashed;
        $this->resetPage();
    }

    public function restore($id)
    {
        try {
            $post = Post::onlyTrashed()->findOrFail($id);
            $post->restore();
            $this->logRestored('post', $post, $post->title);
            $this->successAlert('Restored', 'Post restored successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Could not restore post.');
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->deleteId = $id;
            $post = Post::onlyTrashed()->findOrFail($id);
            $message = "<strong>Permanently delete: \"{$post->title}\"?</strong><br><small class='text-danger'>This will permanently remove the post and cannot be recovered!</small>";
            $this->dispatch('swal:confirm-delete', [
                'title' => 'Permanent Delete',
                'message' => $message,
                'confirmCallback' => 'confirmForceDelete'
            ]);
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Post not found.');
        }
    }

    public function confirmForceDelete()
    {
        try {
            if (!$this->deleteId) return;

            $post = Post::onlyTrashed()->findOrFail($this->deleteId);
            $postTitle = $post->title;

            // Delete image if exists
            if ($post->featured_image && \File::exists(public_path('storage/images/posts/' . $post->featured_image))) {
                \File::delete(public_path('storage/images/posts/' . $post->featured_image));
            }

            $this->logForceDeleted('post', $post, $postTitle);
            $post->forceDelete();
            $this->deleteId = null;
            $this->successAlert('Deleted', 'Post permanently deleted!');
        } catch (\Exception $e) {
            $this->deleteId = null;
            $this->errorAlert('Error', 'Could not permanently delete post.');
        }
    }

    public function delete($id)
    {
        try {
            $post = Post::find($id);
            $this->authorize('delete', $post);
            if ($post) {
                $this->deleteId = $id;
                $message = "<strong>Delete Post: \"{$post->title}\"?</strong><br><small class='text-muted'>This action cannot be undone.</small>";
                $this->dispatch('swal:confirm-delete', [
                    'title' => 'Delete Post',
                    'message' => $message,
                    'confirmCallback' => 'confirmDeletePost'
                ]);
            }
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Something went wrong while loading post.');
        }
    }

    public function confirmDeletePost()
    {
        try {
            if (!$this->deleteId) {
                $this->errorAlert('Error', 'No post selected for deletion.');
                return;
            }

            $post = Post::findOrFail($this->deleteId);

            // Delete image if exists
            if ($post->featured_image && \File::exists(public_path('storage/images/posts/' . $post->featured_image))) {
                \File::delete(public_path('storage/images/posts/' . $post->featured_image));
            }

            $this->logDeleted('post', $post, $post->title);
            $post->delete(); // Use soft delete, not forceDelete
            $this->deleteId = null;
            $this->successAlert('Deleted', 'Post deleted successfully!');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->deleteId = null;
            $this->errorAlert('Error', 'Could not delete post. Please try again.');
        }
    }
}
