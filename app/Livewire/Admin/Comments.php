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

    public function render()
    {
        $comments = Comment::with(['user', 'post'])
            ->when($this->search, function ($q) {
                $q->where('content', 'like', '%' . $this->search . '%')
                    ->orWhereHas('user', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filterStatus, function ($q) {
                $q->where('status', $this->filterStatus);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.comments', [
            'comments' => $comments
        ])->layout('backend.layout.pages-layout', ['pageTitle' => 'Manage Comments']);
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
        $comment = Comment::findOrFail($id);
        $this->authorize('delete', $comment);

        $comment->delete();
        $this->successAlert('Deleted', 'Comment deleted successfully.');
    }
}
