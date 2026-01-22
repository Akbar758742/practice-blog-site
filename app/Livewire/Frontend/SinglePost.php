<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use App\Models\Post;

class SinglePost extends Component
{
    public $post;

    public function mount($slug)
    {
        $this->post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.frontend.single-post')->layout('frontend.layout.pages-layout', [
            'pageTitle' => $this->post->title,
            'metaDesc' => $this->post->meta_desc
        ]);
    }
}
