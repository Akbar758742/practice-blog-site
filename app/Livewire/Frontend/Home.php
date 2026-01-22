<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Post;

class Home extends Component
{
    public function render()
    {
        $featuredPost = Post::where('is_published', true)
            ->whereNotNull('featured_image')
            ->latest('published_at')
            ->first();

        $recentPosts = Post::where('is_published', true)
            ->when($featuredPost, function ($query) use ($featuredPost) {
                return $query->where('id', '!=', $featuredPost->id);
            })
            ->latest('published_at')
            ->take(6)
            ->paginate(6);

        return view('livewire.frontend.home', [
            'featuredPost' => $featuredPost,
            'recentPosts' => $recentPosts,
        ])->layout('frontend.layout.pages-layout', ['pageTitle' => 'Home']);
    }
}
