<div>
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-blue h4">Create Post</h4>
            </div>
            <div class="pull-right">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-primary btn-sm scroll-click" rel="content-y"
                    role="button">Back to Posts</a>
            </div>
        </div>
        <form wire:submit.prevent="store">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Post Title</label>
                        <input type="text" class="form-control" wire:model.live="title" wire:keyup="generateSlug"
                            placeholder="Enter post title">
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" class="form-control" wire:model="slug" placeholder="Post slug" readonly>
                        @error('slug') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <div wire:ignore>
                            <textarea id="content" wire:model="content" class="form-control"
                                style="height: 300px;"></textarea>
                        </div>
                        @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" wire:model="category_id">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Featured Image</label>
                        <input type="file" class="form-control-file form-control height-auto"
                            wire:model="featured_image">
                        @if ($featured_image)
                            <img src="{{ $featured_image->temporaryUrl() }}" width="100" class="mt-2">
                        @endif
                        @error('featured_image') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Tags</label>
                        <div class="d-flex flex-wrap">
                            @foreach($tags as $tag)
                                <div class="custom-control custom-checkbox mb-5 mr-3">
                                    <input type="checkbox" class="custom-control-input" id="tag_{{ $tag->id }}"
                                        value="{{ $tag->id }}" wire:model="selectedTags">
                                    <label class="custom-control-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" wire:model="status">
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="comments_allowed"
                                wire:model="comments_allowed">
                            <label class="custom-control-label" for="comments_allowed">Allow Comments</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Create Post</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/27.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set('content', editor.getData());
                });
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endpush