<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Comment;
use App\Traits\AlertTrait;

class Comments extends Component
{
    use WithPagination, AlertTrait;

    public $search;
    public $filterStatus = '';
    public $deleteId = null;
    public $showTrashed = false;

    public function render()
    {
        $query = Comment::with(['user', 'post'])
            ->when($this->search, function ($q) {
                $q->where('content', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            });

        // Show trashed or active comments
        if ($this->showTrashed) {
            $query->onlyTrashed();
        }

        $comments = $query->latest()->paginate(10);

        // Get trash count
        $trashCount = Comment::onlyTrashed()->count();

        return view('livewire.admin.comments', [
            'comments' => $comments,
            'trashCount' => $trashCount
        ])->layout('backend.layout.pages-layout', ['pageTitle' => $this->showTrashed ? 'Trashed Comments' : 'Manage Comments']);
    }

    public function toggleTrashed()
    {
        $this->showTrashed = !$this->showTrashed;
        $this->resetPage();
    }

    public function restore($id)
    {
        try {
            $comment = Comment::onlyTrashed()->findOrFail($id);
            $comment->restore();
            $this->successAlert('Restored', 'Comment restored successfully!');
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Could not restore comment.');
        }
    }

    public function forceDelete($id)
    {
        try {
            $this->deleteId = $id;
            $comment = Comment::onlyTrashed()->findOrFail($id);
            $message = "<strong>Permanently delete this comment?</strong><br><small class='text-danger'>This will permanently remove the comment and cannot be recovered!</small>";
            $this->dispatch('swal:confirm-delete', [
                'title' => 'Permanent Delete',
                'message' => $message,
                'confirmCallback' => 'confirmForceDelete'
            ]);
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Comment not found.');
        }
    }

    public function confirmForceDelete()
    {
        try {
            if (!$this->deleteId) return;

            $comment = Comment::onlyTrashed()->findOrFail($this->deleteId);
            $comment->forceDelete();
            $this->deleteId = null;
            $this->successAlert('Deleted', 'Comment permanently deleted!');
        } catch (\Exception $e) {
            $this->deleteId = null;
            $this->errorAlert('Error', 'Could not permanently delete comment.');
        }
    }

    public function approve($id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('moderate', $comment); // or update

        $comment->update(['status' => 'approved']);
        $this->successAlert('Approved', 'Comment approved successfully.');
    }

    public function spam($id)
    {
        $comment = Comment::findOrFail($id);
        $this->authorize('moderate', $comment);

        $comment->update(['status' => 'spam']);
        $this->successAlert('Spam', 'Comment marked as spam.');
    }

    public function delete($id)
    {
        try {
            $comment = Comment::findOrFail($id);

            $this->deleteId = $id;
            $postTitle = $comment->post->title ?? 'Unknown';
            $message = "<strong>Delete this comment?</strong><br><small class='text-muted'>From: <em>{$postTitle}</em><br>This action cannot be undone.</small>";
            $this->dispatch('swal:confirm-delete', [
                'title' => 'Delete Comment',
                'message' => $message,
                'confirmCallback' => 'confirmDeleteComment'
            ]);
        } catch (\Exception $e) {
            $this->errorAlert('Error', 'Comment not found.');
        }
    }

    public function confirmDeleteComment()
    {
        try {
            if (!$this->deleteId) {
                $this->errorAlert('Error', 'No comment selected for deletion.');
                return;
            }

            $comment = Comment::findOrFail($this->deleteId);
            $comment->delete(); // Use soft delete
            $this->deleteId = null;
            $this->successAlert('Deleted', 'Comment deleted successfully.');
        } catch (\Exception $e) {
            $this->deleteId = null;
            $this->errorAlert('Error', 'Could not delete comment: ' . $e->getMessage());
        }
    }
}
