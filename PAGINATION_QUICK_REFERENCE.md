# Pagination Component - Quick Reference Card

## 📦 Component File
```
resources/views/components/pagination.blade.php
```

---

## 🚀 Quick Usage

### For Livewire (Admin Pages)
```blade
<x-pagination :items="$users" wirePath="gotoPage" />
```

### For Traditional Views (Frontend)
```blade
<x-pagination :items="$posts" />
```

---

## ⚙️ Setup Checklist

### Livewire Component Setup
```php
// In your Livewire component class

public $currentPage = 1;  // Add this property

public function gotoPage($page)  // Add this method
{
    $this->currentPage = $page;
}

#[Computed]
public function users()  // Or any model name
{
    return User::paginate(20, page: $this->currentPage);
}
```

### View Usage
```blade
<!-- In your Livewire view -->
<x-pagination :items="$this->users" wirePath="gotoPage" />
```

---

## 📋 Where It's Used

| Page | Type | File |
|------|------|------|
| Notifications | Livewire | `livewire/admin/all-notifications.blade.php` |
| Users | Livewire | `livewire/admin/users.blade.php` |
| Posts (Admin) | Livewire | `livewire/admin/posts.blade.php` |
| Pages | Livewire | `livewire/admin/pages.blade.php` |
| Comments | Livewire | `livewire/admin/comments.blade.php` |
| Activity Log | Livewire | `livewire/admin/activity-log.blade.php` |
| Search Posts | Frontend | `livewire/frontend/search-posts.blade.php` |
| Category Posts | Frontend | `livewire/frontend/category-posts.blade.php` |
| Home Posts | Frontend | `livewire/frontend/home.blade.php` |

---

## 🎨 Features at a Glance

✅ Professional blue theme (#5b93ff)  
✅ Responsive mobile design  
✅ Smart page number display  
✅ Record count info  
✅ Previous/Next buttons  
✅ Hover effects & transitions  
✅ Bootstrap compatible  
✅ Zero JS dependencies  
✅ Accessibility ready  

---

## 💡 Tips & Tricks

### Customize Per Page Count
```php
public $perPage = 50;  // Change default

// In query
User::paginate($this->perPage, page: $this->currentPage)
```

### Add Wrapper Styling
```blade
<div class="custom-pagination-wrapper">
    <x-pagination :items="$items" wirePath="gotoPage" />
</div>

<style>
    .custom-pagination-wrapper {
        text-align: center;
    }
</style>
```

### With Filter Integration
```blade
<!-- Filters reset to page 1 -->
<x-pagination :items="$filteredItems" wirePath="gotoPage" />
```

---

## ❌ Common Mistakes

```php
// ❌ WRONG: Missing gotoPage method
<x-pagination :items="$items" wirePath="gotoPage" />

// ❌ WRONG: Not using page parameter
User::paginate(20)  // Will always show page 1

// ❌ WRONG: wirePath on non-Livewire page
<x-pagination :items="$items" wirePath="gotoPage" />  <!-- Frontend -->

// ✅ CORRECT: With page parameter
User::paginate(20, page: $this->currentPage)

// ✅ CORRECT: No wirePath for frontend
<x-pagination :items="$items" />
```

---

## 📱 Responsive Behavior

| Screen | Changes |
|--------|---------|
| Desktop | Full layout, all buttons visible |
| Tablet | Compact spacing, slightly smaller buttons |
| Mobile | Stacked layout, record info on top |

---

## 🔧 Styling Customization

### Colors
```css
.pagination .page-link {
    color: #5b93ff;  /* Change link color */
}

.pagination .page-item.active .page-link {
    background-color: #5b93ff;  /* Change active bg */
}
```

### Size
```css
.pagination .page-link {
    padding: 6px 10px;  /* Adjust padding */
    font-size: 13px;     /* Adjust font size */
}
```

---

## 📞 Support

### Issue: Pagination not working?
1. Check `gotoPage()` method exists
2. Verify `page: $this->currentPage` in paginate()
3. Clear cache: `php artisan view:clear`

### Issue: Styling broken?
1. Verify Bootstrap is loaded
2. Check for CSS conflicts
3. Inspect element in browser DevTools

---

## 📚 Documentation Links

- 📖 [Full Guide](PAGINATION_COMPONENT_GUIDE.md)
- 📊 [Implementation Summary](PAGINATION_IMPLEMENTATION_SUMMARY.md)
- 💻 [Component Code](resources/views/components/pagination.blade.php)

---

**Last Updated:** January 28, 2026  
**Version:** 1.0  
**Status:** ✅ Production Ready
