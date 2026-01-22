<div>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-box mb-30">
                <div class="card-header">
                    <h4 class="text-blue h4">Tags List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tags as $tag)
                                    <tr>
                                        <td>{{ $tag->id }}</td>
                                        <td>{{ $tag->name }}</td>
                                        <td>{{ $tag->slug }}</td>
                                        <td>
                                            <button wire:click="edit({{ $tag->id }})"
                                                class="btn btn-sm btn-primary">Edit</button>
                                            <button wire:click="delete({{ $tag->id }})" class="btn btn-sm btn-danger"
                                                onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-box mb-30">
                <div class="card-header">
                    <h4 class="text-blue h4">{{ $isUpdateMode ? 'Edit Tag' : 'Create Tag' }}</h4>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isUpdateMode ? 'update' : 'store' }}">
                        <div class="form-group">
                            <label>Tag Name</label>
                            <input type="text" class="form-control" wire:model="name" wire:keyup="generateSlug"
                                placeholder="Enter tag name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" class="form-control" wire:model="slug" placeholder="Enter slug">
                            @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">{{ $isUpdateMode ? 'Update' : 'Create' }}</button>
                        <button type="button" wire:click="resetInputFields" class="btn btn-secondary">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>