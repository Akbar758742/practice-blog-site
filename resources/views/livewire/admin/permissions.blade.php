<div>
    <style>
        @media (max-width: 768px) {
            .page-header .row {
                flex-direction: column-reverse;
            }

            .page-header .col-md-6 {
                width: 100%;
                margin-top: 15px;
            }

            .page-header .text-right {
                text-align: left !important;
            }

            .page-header .btn {
                width: 100%;
                margin-bottom: 15px;
            }

            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 8px 5px !important;
            }

            .table-plus {
                min-width: 30px !important;
            }

            .dropdown-menu-right {
                right: -50px !important;
            }

            .nowrap {
                white-space: normal !important;
            }

            .breadcrumb {
                margin-bottom: 10px;
                flex-wrap: wrap;
            }

            .breadcrumb-item {
                font-size: 12px;
            }
        }

        @media (max-width: 480px) {
            .page-header .title h4 {
                font-size: 18px;
                margin-bottom: 10px;
            }

            .data-table th,
            .data-table td {
                padding: 6px 3px !important;
            }

            .data-table {
                font-size: 11px;
            }

            .dropdown {
                position: relative;
            }

            .dropdown-menu {
                position: absolute;
                z-index: 1000;
            }

            .card-box {
                border-radius: 4px;
                overflow-x: auto;
            }

            .pb-20 {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>

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
        <div class="table-responsive pb-20">
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
                            <td>
                                <span class="d-md-none d-block font-weight-bold mb-2">{{ $permission->name }}</span>
                                <span class="d-none d-md-inline">{{ $permission->name }}</span>
                            </td>
                            <td>
                                <code class="text-muted">{{ $permission->slug }}</code>
                            </td>
                            <td>
                                <span class="badge badge-light">{{ $permission->group_name }}</span>
                            </td>
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
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="icon-copy fa fa-inbox fa-2x mb-3 d-block"></i>
                                    No Permissions Found
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Permission Modal -->
    <div wire:ignore.self class="modal fade" id="permissionModal" tabindex="-1" role="dialog"
        aria-labelledby="permissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="permissionModalLabel">
                        {{ $isEdit ? 'Edit Permission' : 'Create Permission' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="{{ $isEdit ? 'updatePermission' : 'storePermission' }}">
                    <div class="modal-body pd-20">
                        <div class="form-group mb-3">
                            <label for="permissionName" class="font-weight-600">Permission Name <span class="text-danger">*</span></label>
                            <input type="text" id="permissionName" class="form-control @error('name') is-invalid @enderror"
                                wire:model.lazy="name" placeholder="e.g. Create Posts">
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="permissionSlug" class="font-weight-600">Slug <span class="text-danger">*</span></label>
                            <input type="text" id="permissionSlug" class="form-control @error('slug') is-invalid @enderror"
                                wire:model.lazy="slug" placeholder="e.g. post.create">
                            @error('slug')
                                <div class="invalid-feedback d-block">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <label for="groupName" class="font-weight-600">Group Name <span class="text-danger">*</span></label>
                            <input type="text" id="groupName" class="form-control @error('group_name') is-invalid @enderror"
                                wire:model.lazy="group_name" placeholder="e.g. posts">
                            @error('group_name')
                                <div class="invalid-feedback d-block">
                                    <i class="fa fa-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-top pt-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>{{ $isEdit ? 'Update Permission' : 'Create Permission' }}</span>
                            <span wire:loading>
                                <i class="fa fa-spinner fa-spin"></i> Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
