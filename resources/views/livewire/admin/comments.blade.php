<div>
    <div class="pd-20 card-box mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h4 class="text-blue h4">Manage Comments</h4>
            </div>
            <div class="pull-right">
                <div class="form-group mb-0">
                    <input type="text" class="form-control form-control-sm" placeholder="Search..." wire:model.live="search">
                </div>
            </div>
        </div>
        
        <div class="mb-20">
             <button class="btn btn-sm btn-outline-primary {{ $filterStatus == '' ? 'active' : '' }}" wire:click="$set('filterStatus', '')">All</button>
             <button class="btn btn-sm btn-outline-warning {{ $filterStatus == 'pending' ? 'active' : '' }}" wire:click="$set('filterStatus', 'pending')">Pending</button>
             <button class="btn btn-sm btn-outline-success {{ $filterStatus == 'approved' ? 'active' : '' }}" wire:click="$set('filterStatus', 'approved')">Approved</button>
             <button class="btn btn-sm btn-outline-danger {{ $filterStatus == 'spam' ? 'active' : '' }}" wire:click="$set('filterStatus', 'spam')">Spam</button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Author</th>
                        <th>Content</th>
                        <th>Post</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr>
                            <td>
                                {{ $comment->user->name ?? 'Guest' }} <br>
                                <small class="text-muted">{{ $comment->user->email ?? '' }}</small>
                            </td>
                            <td>
                                <div style="max-width: 300px; white-space: normal;">
                                    {{ Str::limit($comment->content, 100) }}
                                </div>
                            </td>
                            <td>
                                @if($comment->post)
                                <a href="#" target="_blank">{{ Str::limit($comment->post->title, 30) }}</a>
                                @else
                                <span class="text-muted">Deleted Post</span>
                                @endif
                            </td>
                            <td>
                                @if($comment->status == 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($comment->status == 'approved')
                                    <span class="badge badge-success">Approved</span>
                                @else
                                    <span class="badge badge-danger">Spam</span>
                                @endif
                            </td>
                            <td>{{ $comment->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        @can('moderate', $comment)
                                            @if($comment->status !== 'approved')
                                                <a class="dropdown-item" href="#" wire:click.prevent="approve({{ $comment->id }})"><i class="dw dw-check"></i> Approve</a>
                                            @endif
                                            @if($comment->status !== 'spam')
                                                <a class="dropdown-item" href="#" wire:click.prevent="spam({{ $comment->id }})"><i class="dw dw-ban"></i> Spam</a>
                                            @endif
                                        @endcan
                                        
                                        @can('delete', $comment)
                                            <a class="dropdown-item" href="#" wire:click.prevent="delete({{ $comment->id }})" 
                                            onclick="confirm('Are you sure you want to delete this comment?') || event.stopImmediatePropagation()"><i class="dw dw-delete-3"></i> Delete</a>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No comments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $comments->links() }}
        </div>
    </div>
</div>
