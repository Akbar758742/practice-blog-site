<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Permissions</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Permissions</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <button wire:click="createPermission" class="btn btn-primary" data-toggle="modal"
                    data-target="#permissionModal">
                    <i class="icon-copy dw dw-add"></i> Add Permission
                </button>
            </div>
        </div>
    </div>

    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">All Permissions</h4>
        </div>
        <div class="pb-20">
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
                        <th class="table-plus datatable-nosort">ID</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Group</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        <tr>
                            <td class="table-plus">{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->slug }}</td>
                            <td>{{ $permission->group_name }}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#"
                                        role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            wire:click="editPermission({{ $permission->id }})" data-toggle="modal"
                                            data-target="#permissionModal"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            wire:click="deletePermission({{ $permission->id }})"><i
                                                class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Permissions Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permission Modal -->
    <div wire:ignore.self class="modal fade" id="permissionModal" tabindex="-1" role="dialog"
        aria-labelledby="permissionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalLabel">
                        {{ $isEdit ? 'Edit Permission' : 'Create Permission' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="{{ $isEdit ? 'updatePermission' : 'storePermission' }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Permission Name</label>
                            <input type="text" class="form-control" wire:model="name"
                                placeholder="Enter permission name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" class="form-control" wire:model="slug"
                                placeholder="Enter permission slug">
                            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Group Name</label>
                            <input type="text" class="form-control" wire:model="group_name"
                                placeholder="Enter group name (e.g. posts)">
                            @error('group_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>