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

    public $postsToday;
    public $postsThisWeek;
    public $newUsersThisWeek;

    public $postsWithoutCategory;
    public $postsWithoutImage;
    // public $postsWithoutMeta; // meta_desc is nullable, checking null
    public $oldDrafts;
    public $postsNeedingUpdate;

    public $activeFilter = 'latest'; // latest, drafts, scheduled

    public function setFilter($filter)
    {
        $this->activeFilter = $filter;
        $this->loadRecentPosts();
    }

    public function loadRecentPosts()
    {
        $query = Post::with('user', 'category');

        if ($this->activeFilter === 'drafts') {
            $query->where('is_published', false);
        } elseif ($this->activeFilter === 'scheduled') {
            // Assuming scheduled means published_at > now or specific status
            $query->where('is_published', true)->where('published_at', '>', now());
        } else {
            // Default to latest published or just latest created
            if ($this->activeFilter === 'published') {
                $query->where('is_published', true)->where('published_at', '<=', now());
            }
        }

        $this->recentPosts = $query->latest()->take(5)->get();
    }

    public function mount()
    {
        $this->totalPosts = Post::count();
        $this->totalCategories = Category::count();
        $this->totalTags = Tag::count();
        $this->totalUsers = User::count();

        $this->publishedPosts = Post::where('is_published', true)->count();
        $this->draftPosts = Post::where('is_published', false)->count();

        // 1. Time-based Metrics
        $this->postsToday = Post::whereDate('created_at', today())->count();
        $this->postsThisWeek = Post::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('is_published', true)->count();
        $this->newUsersThisWeek = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        // 2. Content Health
        $this->postsWithoutCategory = Post::whereNull('category_id')->count();
        $this->postsWithoutImage = Post::whereNull('featured_image')->count();
        $this->oldDrafts = Post::where('is_published', false)->where('updated_at', '<', now()->subDays(30))->count();
        $this->postsNeedingUpdate = Post::where('is_published', true)->where('updated_at', '<', now()->subMonths(6))->count();

        // 3. Initial Recent Posts
        $this->loadRecentPosts();

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
