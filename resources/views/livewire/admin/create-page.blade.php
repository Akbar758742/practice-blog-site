<div>
    <div class="pd-20 card-box mb-30">
        <div class="clearfix">
            <div class="pull-left">
                <h4 class="text-blue h4">Create Page</h4>
            </div>
            <div class="pull-right">
                <a href="{{ route('admin.pages.index') }}" class="btn btn-primary btn-sm scroll-click" rel="content-y"
                    role="button">Back to Pages</a>
            </div>
        </div>
        <form wire:submit.prevent="store">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Page Title</label>
                        <input type="text" class="form-control" wire:model.live="title" wire:keyup="generateSlug"
                            placeholder="Enter page title">
                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Slug</label>
                        <input type="text" class="form-control" wire:model="slug" placeholder="Page slug" readonly>
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
                        <label>Visibility</label>
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="is_visible" wire:model="is_visible">
                            <label class="custom-control-label" for="is_visible">Visible</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" class="form-control" wire:model="meta_title" placeholder="SEO Title">
                    </div>
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea class="form-control" wire:model="meta_desc" placeholder="SEO Description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Create Page</button>
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