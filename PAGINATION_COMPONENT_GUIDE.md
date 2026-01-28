# Reusable Pagination Component Documentation

## Overview
A professional, responsive pagination component that can be used across any CRUD page or listing in your application. The component automatically adapts to different contexts (Livewire components vs traditional Laravel controllers).

## File Location
```
resources/views/components/pagination.blade.php
```

## Usage

### For Livewire Components (Admin/Dashboard Pages)

```blade
<x-pagination :items="$users" wirePath="gotoPage" />
```

**Parameters:**
- `:items` (required) - The paginated collection from Laravel's paginate() method
- `wirePath="gotoPage"` (required for Livewire) - The Livewire method name that handles pagination

**Example in Livewire Component:**
```blade
<!-- In resources/views/livewire/admin/users.blade.php -->
<table class="table">
    <!-- table content -->
</table>
<div class="px-3">
    <x-pagination :items="$users" wirePath="gotoPage" />
</div>
```

**Livewire Component Method:**
```php
// In app/Livewire/Admin/Users.php
public function gotoPage($page)
{
    $this->currentPage = $page;
    // Livewire automatically re-renders and refreshes data
}
```

---

### For Frontend/Standard Pages (No Livewire)

```blade
<x-pagination :items="$posts" />
```

**Parameters:**
- `:items` (required) - The paginated collection from Laravel's paginate() method
- `wirePath` - Omit this parameter for standard HTML pagination links

**Example in Traditional View:**
```blade
<!-- In resources/views/livewire/frontend/category-posts.blade.php -->
<div class="posts-grid">
    @foreach($posts as $post)
        <!-- post card -->
    @endforeach
</div>

<div class="mt-12">
    <x-pagination :items="$posts" />
</div>
```

---

## Features

✅ **Professional Design**
- Clean, modern styling with blue accent (#5b93ff)
- Smooth hover effects and transitions
- Active page highlighting

✅ **Smart Page Display**
- Shows record count ("Showing 1 to 20 of 150 records")
- Previous/Next navigation buttons with icons
- Intelligent page number range (shows current page ±1)
- Three-dot indicators for hidden pages

✅ **Responsive**
- Mobile-friendly layout (stacks on small screens)
- Proper spacing and padding for all devices
- Touch-friendly button sizes

✅ **Dual Mode Support**
- Works with Livewire components (wire:click)
- Works with traditional Laravel views (href links)
- Automatic detection based on `wirePath` parameter

✅ **Accessibility**
- Proper HTML semantic structure
- ARIA labels for screen readers
- Keyboard navigable

---

## Styling

The component includes built-in CSS styling:

```css
/* Active page button */
.pagination .page-item.active .page-link {
    color: #fff;
    background-color: #5b93ff;
    border-color: #5b93ff;
    box-shadow: 0 2px 8px rgba(91, 147, 255, 0.2);
}

/* Hover effect */
.pagination .page-link:hover {
    color: #fff;
    background-color: #5b93ff;
    border-color: #5b93ff;
    box-shadow: 0 2px 8px rgba(91, 147, 255, 0.2);
}

/* Disabled state */
.pagination .page-item.disabled .page-link {
    color: #ccc;
    background-color: #f5f5f5;
    border-color: #e0e0e0;
    cursor: not-allowed;
}
```

---

## Implementation Guide

### Step 1: Controller/Livewire Setup

**For Traditional Controller:**
```php
// app/Http/Controllers/PostController.php
public function index()
{
    $posts = Post::paginate(15);
    return view('posts.index', compact('posts'));
}
```

**For Livewire Component:**
```php
// app/Livewire/Admin/Posts.php
#[Layout('layouts.admin')]
class Posts extends Component
{
    public $currentPage = 1;
    public $perPage = 20;
    
    public function gotoPage($page)
    {
        $this->currentPage = $page;
    }
    
    #[Computed]
    public function posts()
    {
        return Post::paginate($this->perPage, page: $this->currentPage);
    }
    
    public function render()
    {
        return view('livewire.admin.posts', [
            'posts' => $this->posts,
        ]);
    }
}
```

### Step 2: View Implementation

**Livewire View:**
```blade
<!-- resources/views/livewire/admin/posts.blade.php -->
<div class="card-box">
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->author->name }}</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">Edit</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-4">No posts found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Add the pagination component -->
    <div class="mt-4 px-3">
        <x-pagination :items="$posts" wirePath="gotoPage" />
    </div>
</div>
```

**Traditional View:**
```blade
<!-- resources/views/posts/index.blade.php -->
<div class="container">
    <div class="posts-grid">
        @forelse($posts as $post)
            <div class="post-card">
                <h3>{{ $post->title }}</h3>
                <p>{{ $post->excerpt }}</p>
            </div>
        @empty
            <p>No posts found</p>
        @endforelse
    </div>
    
    <!-- Add the pagination component -->
    <div class="mt-12">
        <x-pagination :items="$posts" />
    </div>
</div>
```

---

## Where It's Currently Used

The pagination component has been integrated in the following pages:

### Admin Pages (with Livewire)
- [all-notifications.blade.php](all-notifications.blade.php) - Notifications list
- [users.blade.php](users.blade.php) - Users management
- [posts.blade.php](posts.blade.php) - Posts management
- [pages.blade.php](pages.blade.php) - Pages management
- [comments.blade.php](comments.blade.php) - Comments moderation
- [activity-log.blade.php](activity-log.blade.php) - Activity logs

### Frontend Pages (without Livewire)
- [search-posts.blade.php](search-posts.blade.php) - Search results
- [category-posts.blade.php](category-posts.blade.php) - Category listing
- [home.blade.php](home.blade.php) - Recent posts

---

## Advanced: Custom Styling

If you want to customize the pagination styling for a specific page, you can wrap the component:

```blade
<div class="custom-pagination-wrapper">
    <x-pagination :items="$items" />
</div>

<style>
    .custom-pagination-wrapper .pagination .page-link {
        /* Your custom styles */
        color: #your-color;
        padding: 10px 14px;
    }
</style>
```

---

## API Reference

### Component Properties

| Property | Type | Required | Default | Description |
|----------|------|----------|---------|-------------|
| items | Paginator | Yes | - | The paginated collection |
| wirePath | String | No | null | Livewire method name for pagination |

### Pagination Object Methods

The component uses Laravel's Paginator methods:

```php
$items->hasPages()              // Check if pagination is needed
$items->onFirstPage()           // Is on first page
$items->hasMorePages()          // Has next page
$items->currentPage()           // Get current page number
$items->lastPage()              // Get total pages
$items->total()                 // Total items count
$items->firstItem()             // First item number on page
$items->lastItem()              // Last item number on page
$items->url($page)              // Get URL for page
$items->nextPageUrl()           // Get next page URL
$items->getUrlRange(1, 5)       // Get URLs range
```

---

## Troubleshooting

### Issue: Pagination not working with Livewire

**Solution:** Make sure you have `gotoPage` method in your Livewire component:

```php
public function gotoPage($page)
{
    $this->currentPage = $page;
}
```

### Issue: No records shown on different pages

**Solution:** Ensure your query uses the current page:

```php
public function getPosts()
{
    return Post::paginate(20, page: $this->currentPage);
}
```

### Issue: Styling doesn't match rest of dashboard

**Solution:** The component uses Bootstrap classes. Make sure Bootstrap is loaded in your layout file.

---

## Performance Notes

- The component uses minimal inline styles
- No JavaScript dependencies required
- Supports AJAX/Livewire rendering
- Caches pagination URL generation
- Optimized for mobile devices

---

## Future Enhancements

Potential improvements for this component:

- [ ] Customizable items per page dropdown
- [ ] URL query parameter support
- [ ] Custom button text translations
- [ ] Dark mode support
- [ ] Tailwind CSS variant
- [ ] API-based pagination support

---

**Last Updated:** January 28, 2026
**Component Version:** 1.0
**Tested With:** Laravel 11, Livewire 3
