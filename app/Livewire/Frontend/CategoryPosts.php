<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Category;
use App\Models\Post;

class CategoryPosts extends Component
{
    public $category;

    public function mount($slug)
    {
        $this->category = Category::where('slug', $slug)->firstOrFail();
    }

    public function render()
    {
        $posts = Post::where('category_id', $this->category->id)
            ->where('is_published', true)
            ->latest('published_at')
            ->paginate(12);

        return view('livewire.frontend.category-posts', [
            'posts' => $posts
        ])->layout('frontend.layout.pages-layout', ['pageTitle' => $this->category->name]);
    }
}
