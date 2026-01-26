<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class PostComments extends Component
{
    public $post;
    public $content;
    public $guest_name;
    public $guest_email;
    public $replyingTo = null; // Comment ID being replied to
    public $replyingToName = '';

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function startReply($commentId, $userName)
    {
        $this->replyingTo = $commentId;
        $this->replyingToName = $userName;
        $this->content = ''; // Clear or keep? Usually clear for new reply in same box, or move focus.
        // If we want to focus the textarea, we can use JS.
    }

    public function cancelReply()
    {
        $this->replyingTo = null;
        $this->replyingToName = '';
    }

    public function postComment()
    {
        $rules = [
            'content' => 'required|string|min:3|max:1000',
        ];

        if (!Auth::check()) {
            $rules['guest_name'] = 'required|string|max:50';
            $rules['guest_email'] = 'required|email|max:100';
        }

        $this->validate($rules);

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => Auth::id(), // Null if guest
            'guest_name' => Auth::check() ? null : $this->guest_name,
            'guest_email' => Auth::check() ? null : $this->guest_email,
            'parent_id' => $this->replyingTo,
            'content' => $this->content,
            'status' => 'pending', // Pending moderation
        ]);

        $this->content = '';
        if (!Auth::check()) {
            $this->guest_name = '';
            $this->guest_email = '';
        }
        $this->cancelReply();
        session()->flash('message', 'Comment submitted! It will appear after approval.'); // Standard flash
        $this->dispatch('swal:success', ['message' => 'Comment submitted! It will appear after approval.']);
    }

    public function render()
    {
        $comments = $this->post->comments()
            ->whereNull('parent_id') // Top level only
            ->where('status', 'approved') // Only show approved comments
            ->orderBy('created_at', 'desc')
            ->with([
                'user',
                'replies' => function ($q) {
                    $q->where('status', 'approved')->with('user');
                }
            ])
            ->get();

        return view('livewire.frontend.post-comments', [
            'comments' => $comments
        ]);
    }
}
