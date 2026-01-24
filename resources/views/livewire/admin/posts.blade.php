<div>
    <div class="card card-box mb-30">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h4 class="text-blue h4">All Posts</h4>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-sm">Create New Post</a>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Search posts..." wire:model.live="search">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Date</th>
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
                                <td>{{ $post->created_at ? $post->created_at->format('d M, Y') : '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.posts.edit', $post->id) }}"
                                        class="btn btn-sm btn-info">Edit</a>
                                    <button wire:click="delete({{ $post->id }})" class="btn btn-sm btn-danger"
                                        onclick="confirm('Are you sure you want to delete this post?') || event.stopImmediatePropagation()">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No posts found.</td>
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