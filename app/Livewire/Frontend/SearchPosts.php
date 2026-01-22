<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Models\Post;

class SearchPosts extends Component
{
    #[Url]
    public $q = '';

    public function render()
    {
        $posts = [];
        if ($this->q) {
            $posts = Post::where('is_published', true)
                ->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->q . '%')
                        ->orWhere('content', 'like', '%' . $this->q . '%');
                })
                ->latest('published_at')
                ->paginate(12);
        } else {
            $posts = Post::where('is_published', true)->latest('published_at')->paginate(12);
        }

        return view('livewire.frontend.search-posts', [
            'posts' => $posts
        ])->layout('frontend.layout.pages-layout', ['pageTitle' => 'Search Results for ' . $this->q]);
    }
}
