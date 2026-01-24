<div>
    <div class="pd-20 card-box mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h4 class="text-blue h4">All Pages</h4>
            </div>
            <div class="pull-right">
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm scroll-click" rel="content-y"
                    role="button">Add New Page</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Visibility</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td>{{ $page->title }}</td>
                            <td>{{ $page->slug }}</td>
                            <td>
                                @if($page->is_visible)
                                    <span class="badge badge-success">Visible</span>
                                @else
                                    <span class="badge badge-secondary">Hidden</span>
                                @endif
                            </td>
                            <td>{{ $page->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#"
                                        role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="{{ route('admin.pages.edit', $page->id) }}"><i
                                                class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="#" wire:click.prevent="delete({{ $page->id }})"
                                            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"><i
                                                class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No pages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $pages->links() }}
        </div>
    </div>
</div>