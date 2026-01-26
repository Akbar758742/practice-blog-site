<div>
    <div class="pd-20 card-box mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h4 class="text-blue h4">{{ $showTrashed ? 'Trashed Pages' : 'All Pages' }}</h4>
            </div>
            <div class="pull-right">
                @if(!$showTrashed)
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary btn-sm scroll-click" rel="content-y"
                        role="button">Add New Page</a>
                @endif
                <button wire:click="toggleTrashed" class="btn btn-{{ $showTrashed ? 'secondary' : 'danger' }} btn-sm">
                    @if($showTrashed)
                        <i class="icon-copy fa fa-arrow-left"></i> Back to Pages
                    @else
                        <i class="icon-copy fa fa-trash"></i> Trash
                        @if($trashCount > 0)
                            <span class="badge badge-light">{{ $trashCount }}</span>
                        @endif
                    @endif
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Visibility</th>
                        <th>{{ $showTrashed ? 'Deleted At' : 'Date' }}</th>
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
                            <td>{{ $showTrashed ? $page->deleted_at->format('d M Y H:i') : $page->created_at->format('d M Y') }}</td>
                            <td>
                                @if($showTrashed)
                                    <button wire:click="restore({{ $page->id }})" class="btn btn-success btn-sm" title="Restore">
                                        <i class="icon-copy fa fa-undo"></i> Restore
                                    </button>
                                    <button wire:click="forceDelete({{ $page->id }})" class="btn btn-danger btn-sm" title="Delete Forever">
                                        <i class="icon-copy fa fa-times"></i> Delete Forever
                                    </button>
                                @else
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#"
                                            role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <a class="dropdown-item" href="{{ route('admin.pages.edit', $page->id) }}"><i
                                                    class="dw dw-edit2"></i> Edit</a>
                                            <a class="dropdown-item" href="#" wire:click.prevent="delete({{ $page->id }})"
                                               ><i
                                                    class="dw dw-delete-3"></i> Delete</a>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                {{ $showTrashed ? 'No pages in trash.' : 'No pages found.' }}
                            </td>
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
