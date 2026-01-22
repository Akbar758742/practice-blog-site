
<div>
    <div class="row">
        <div class="col-md-8">
            <div class="card card-box mb-30">
                <div class="card-header">
                    <h4 class="text-blue h4">Categories List</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Parent</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->slug }}</td>
                                    <td>{{ $category->parent ? $category->parent->name : '-' }}</td>
                                    <td>
                                        <button wire:click="edit({{ $category->id }})" class="btn btn-sm btn-primary">Edit</button>
                                        <button wire:click="delete({{ $category->id }})" class="btn btn-sm btn-danger" onclick="confirm('Are you sure?') || event.stopImmediatePropagation()">Delete</button>
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
                    <h4 class="text-blue h4">{{ $isUpdateMode ? 'Edit Category' : 'Create Category' }}</h4>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="{{ $isUpdateMode ? 'update' : 'store' }}">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" class="form-control" wire:model="name" wire:keyup="generateSlug" placeholder="Enter category name">
                            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" class="form-control" wire:model="slug" placeholder="Enter slug">
                           @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label>Parent Category</label>
                            <select class="form-control" wire:model="parent_id">
                                <option value="">None</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ $isUpdateMode ? 'Update' : 'Create' }}</button>
                         <button type="button" wire:click="resetInputFields" class="btn btn-secondary">Cancel</button>
                    </form>
                </div>
             </div>
        </div>
    </div>
</div>
