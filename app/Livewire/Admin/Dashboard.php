<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalPosts;
    public $totalCategories;
    public $totalTags;
    public $totalUsers;

    public $publishedPosts;
    public $draftPosts;
    public $scheduledPosts;

    public $recentPosts;
    public $popularPosts;

    public function mount()
    {
        $this->totalPosts = Post::count();
        $this->totalCategories = Category::count();
        $this->totalTags = Tag::count();
        $this->totalUsers = User::count();

        $this->publishedPosts = Post::where('is_published', true)->count();
        $this->draftPosts = Post::where('is_published', false)->count();
        // Assuming scheduled posts might be handled differently or just defined by published_at > now
        // For now, let's stick to the 'is_published' flag or simple logic. 
        // If there's no specific 'scheduled' status, we can omit it or refine logic.
        // Let's assume draft = !is_published. Additional status logic can be added later.

        $this->recentPosts = Post::with('user', 'category')
            ->latest()
            ->take(5)
            ->get();

        $this->popularPosts = Post::with('user')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // System Status
        $this->laravelVersion = app()->version();
        $this->appEnvironment = app()->environment();
        $this->databaseName = config('database.connections.' . config('database.default') . '.database');
        // Simple config checks
        $this->cacheStatus = config('cache.default');
    }

    // System Status Properties
    public $laravelVersion;
    public $appEnvironment;
    public $databaseName;
    public $cacheStatus;

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
