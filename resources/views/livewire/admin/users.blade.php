<div>
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>User Management</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Users</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <button wire:click="create" class="btn btn-primary" data-toggle="modal" data-target="#userModal">
                    <i class="icon-copy dw dw-add"></i> Add User
                </button>
            </div>
        </div>
    </div>

    <div class="card-box mb-30">
        <div class="pd-20 d-flex justify-content-between">
            <h4 class="text-blue h4">All Users</h4>
            <div class="w-25">
                <input type="text" class="form-control" placeholder="Search users..." wire:model.live="search">
            </div>
        </div>
        <div class="pb-20">
            <table class="data-table table stripe hover nowrap">
                <thead>
                    <tr>
                        <th class="table-plus">Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="table-plus">
                                <div class="name-avatar d-flex align-items-center">
                                    <div class="avatar mr-2 flex-shrink-0">
                                        <img src="{{ $user->picture }}" class="border-radius-100 shadow" width="40"
                                            height="40" alt="">
                                    </div>
                                    <div class="txt">
                                        <div class="weight-600">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge badge-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($user->status === \App\UserStatus::ACTIVE)
                                    <!-- Assuming UserStatus enum usage or simple string -->
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#"
                                        role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            wire:click="edit({{ $user->id }})" data-toggle="modal"
                                            data-target="#userModal"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                            wire:click="delete({{ $user->id }})"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Users Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div wire:ignore.self class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">{{ $user_id ? 'Edit User' : 'Create User' }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent="{{ $user_id ? 'update' : 'store' }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" wire:model="name" placeholder="Enter full name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" class="form-control" wire:model="email" placeholder="Enter email">
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Password {{ $user_id ? '(Leave blank to keep current)' : '' }}</label>
                            <input type="password" class="form-control" wire:model="password"
                                placeholder="Enter password">
                            @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Assign Role</label>
                            <select class="form-control" wire:model.live="role_id">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">{{ $user_id ? 'Update' : 'Create' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingUserDeletion)
    <div class="modal show d-block" style="background-color: rgba(0,0,0,0.5); z-index: 9999;" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fa fa-exclamation-triangle"></i> Confirm User Deletion
                    </h5>
                    <button type="button" class="close text-white" wire:click="cancelDelete" style="cursor: pointer;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3" role="alert">
                        <strong>⚠️ Warning!</strong> You are about to permanently delete the user account: <strong>{{ $userToDeleteName }}</strong>
                    </div>
                    
                    @if($userToDeletePostCount > 0 || $userToDeleteCommentCount > 0)
                    <p class="mb-2">This user has the following associated content that will also be <strong class="text-danger">permanently deleted</strong>:</p>
                    <ul class="list-group list-group-flush mb-3">
                        @if($userToDeletePostCount > 0)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Posts</span>
                            <span class="badge badge-danger badge-pill">{{ $userToDeletePostCount }}</span>
                        </li>
                        @endif
                        @if($userToDeleteCommentCount > 0)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Comments</span>
                            <span class="badge badge-danger badge-pill">{{ $userToDeleteCommentCount }}</span>
                        </li>
                        @endif
                    </ul>
                    @endif
                    
                    <div class="alert alert-danger mt-3 mb-0">
                        <strong class="d-block mb-2">⛔ This action CANNOT be undone!</strong>
                        <small>All user data, posts, and comments will be permanently deleted from the system.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="cancelDelete">
                        <i class="fa fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-danger" wire:click="confirmDelete">
                        <i class="fa fa-trash"></i> Yes, Delete User & Content
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>