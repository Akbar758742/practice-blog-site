<div>
    <div class="card card-box mb-30">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h4 class="text-blue h4">{{ $showTrashed ? 'Trashed Posts' : 'All Posts' }}</h4>
            <div>
                <button wire:click="toggleTrashed" class="btn btn-{{ $showTrashed ? 'secondary' : 'warning' }} btn-sm mr-2">
                    <i class="fa fa-trash"></i>
                    {{ $showTrashed ? 'View Active Posts' : 'View Trash' }}
                    @if(!$showTrashed && $trashCount > 0)
                        <span class="badge badge-light">{{ $trashCount }}</span>
                    @endif
                </button>
                @if(!$showTrashed)
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">Create New Post</a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search posts..." wire:model.live="search">
                </div>
            </div>

            @if($showTrashed)
            <div class="alert alert-warning mb-3">
                <i class="fa fa-info-circle"></i> These posts are in trash. You can restore them or permanently delete them.
            </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Comments</th>
                            <th>{{ $showTrashed ? 'Deleted At' : 'Date' }}</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td>
                                    @if($post->featured_image)
                                        <img src="{{ asset('storage/images/posts/' . $post->featured_image) }}" alt=""
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($post->title, 40) }}</td>
                                <td>{{ $post->category ? $post->category->name : 'Uncategorized' }}</td>
                                <td>
                                    @if($post->status === \App\Enums\PostStatus::Published)
                                        <span class="badge badge-success">Published</span>
                                    @elseif($post->status === \App\Enums\PostStatus::Draft)
                                        <span class="badge badge-warning">Draft</span>
                                    @elseif($post->status === \App\Enums\PostStatus::Pending)
                                        <span class="badge badge-info">Pending</span>
                                    @else
                                        <span class="badge badge-secondary">Archived</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-secondary">{{ $post->comments_count }}</span>
                                </td>
                                <td>{{ $showTrashed ? ($post->deleted_at ? $post->deleted_at->format('d M, Y') : '-') : ($post->created_at ? $post->created_at->format('d M, Y') : '-') }}</td>
                                <td>
                                    @if($showTrashed)
                                        <button wire:click="restore({{ $post->id }})" class="btn btn-sm btn-success" title="Restore">
                                            <i class="fa fa-undo"></i> Restore
                                        </button>
                                        <button wire:click="forceDelete({{ $post->id }})" class="btn btn-sm btn-danger" title="Delete Permanently">
                                            <i class="fa fa-times"></i> Delete Forever
                                        </button>
                                    @else
                                        <a href="{{ route('admin.posts.edit', $post->id) }}"
                                            class="btn btn-sm btn-info">Edit</a>
                                        <button wire:click="delete({{ $post->id }})" class="btn btn-sm btn-danger">Delete</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    {{ $showTrashed ? 'No trashed posts found.' : 'No posts found.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
