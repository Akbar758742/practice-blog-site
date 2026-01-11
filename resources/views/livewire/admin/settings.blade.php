<div>
   <div class="tab">
    <ul class="nav nav-tabs customtab" role="tablist">
        <li class="nav-item">
            <a wire:click="selectTab('general_settings')" class="nav-link {{ $tab=='general_settings' ? 'active' : '' }}" data-toggle="tab" href="#general_settings" role="tab" aria-selected="false">general settings</a>
        </li>
        <li class="nav-item">
            <a wire:click="selectTab('logo_favicon')" class="nav-link {{ $tab=='logo_favicon' ? 'active' : '' }}" data-toggle="tab" href="#logo_favicon" role="tab" aria-selected="false">logo & favicon</a>
        </li>

    </ul>
    <div class="tab-content">
        <div class="tab-pane fade  {{ $tab=='general_settings' ? 'show active' : '' }}" id="general_settings" role="tabpanel">
            <div class="pd-20">
                <form wire:submit='updateSettings()'>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>site title</label>
                                <input type="text" class="form-control" wire:model='site_title' placeholder="site title">
                                @error('site_title') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>site tagline</label>
                                <input type="text" class="form-control" wire:model='site_tagline' placeholder="site tagline">
                                @error('site_tagline') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>site email</label>
                                <input type="email" class="form-control" wire:model='site_email' placeholder="site email">
                                @error('site_email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>site phone</label>
                                <input type="email" class="form-control" wire:model='site_phone' placeholder="site email">
                                @error('site_phone') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>site meta keywords</label>
                                <input type="email" class="form-control" wire:model='site_meta_keywords' placeholder="site meta keywords">
                                @error('site_meta_keywords') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>site meta description</label>
                                <textarea class="form-control" wire:model='site_meta_description' placeholder="site meta description"></textarea>
                                @error('site_meta_description') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">update settings</button>
                            </div>
                        </div>
                    </div>
                </form>
           </div>
            </div>
        </div>
        <div class="tab-pane fade  {{ $tab=='logo_favicon' ? 'show active' : '' }}" id="logo_favicon" role="tabpanel">
            <div class="pd-20">
               fewrwe
            </div>
        </div>

    </div>
</div>
</div>
