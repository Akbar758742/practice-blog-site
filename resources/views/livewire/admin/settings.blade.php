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
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label>site tagline</label>
                                <input type="text" class="form-control" wire:model='site_tagline' placeholder="site tagline">
                                @error('site_tagline') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div> --}}
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
               <div class="row">
                {{-- Site Logo Section --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <h5 class="mb-3">Site Logo</h5>
                        <div class="mb-3" style="max-width:200px; min-height:80px; background:#f5f5f5; display:flex; align-items:center; justify-content:center; border-radius:8px; padding:10px;">
                            @if($site_logo)
                                <img src="{{ $site_logo->temporaryUrl() }}" alt="New Logo Preview" style="max-width:100%; max-height:100px;">
                            @elseif($existing_site_logo)
                                <img src="{{ asset('storage/' . $existing_site_logo) }}" alt="Current Logo" style="max-width:100%; max-height:100px;">
                            @else
                                <span class="text-muted">No logo uploaded</span>
                            @endif
                        </div>
                        <form wire:submit.prevent="updateLogo">
                            <div class="mb-2">
                               <input type="file" wire:model="site_logo" id="site_logo" class="form-control" accept="image/*">
                                @error('site_logo') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="site_logo,updateLogo">Update Logo</span>
                                <span wire:loading wire:target="site_logo,updateLogo">Uploading...</span>
                            </button>
                        </form>
                    </div>
               </div>

                {{-- Favicon Section --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <h5 class="mb-3">Favicon</h5>
                        <div class="mb-3" style="max-width:100px; min-height:80px; background:#f5f5f5; display:flex; align-items:center; justify-content:center; border-radius:8px; padding:10px;">
                            @if($site_favicon)
                                <img src="{{ $site_favicon->temporaryUrl() }}" alt="New Favicon Preview" style="max-width:64px; max-height:64px;">
                            @elseif($existing_site_favicon)
                                <img src="{{ asset('storage/' . $existing_site_favicon) }}" alt="Current Favicon" style="max-width:64px; max-height:64px;">
                            @else
                                <span class="text-muted">No favicon</span>
                            @endif
                        </div>
                        <form wire:submit.prevent="updateFavicon">
                            <div class="mb-2">
                               <input type="file" wire:model="site_favicon" id="site_favicon" class="form-control" accept="image/*,.ico">
                                @error('site_favicon') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="site_favicon,updateFavicon">Update Favicon</span>
                                <span wire:loading wire:target="site_favicon,updateFavicon">Uploading...</span>
                            </button>
                        </form>
                    </div>
               </div>

                {{-- Sign-in Page Image Section --}}
                <div class="col-md-12 mt-4">
                    <div class="form-group">
                        <h5 class="mb-3">Sign-in Page Image</h5>
                        <div class="mb-3" style="max-width:400px; min-height:150px; background:#f5f5f5; display:flex; align-items:center; justify-content:center; border-radius:8px; padding:10px;">
                            @if($signin_image)
                                <img src="{{ $signin_image->temporaryUrl() }}" alt="New Sign-in Image Preview" style="max-width:100%; max-height:200px;">
                            @elseif($existing_signin_image)
                                <img src="{{ asset('storage/' . $existing_signin_image) }}" alt="Current Sign-in Image" style="max-width:100%; max-height:200px;">
                            @else
                                <span class="text-muted">No sign-in image uploaded</span>
                            @endif
                        </div>
                        <form wire:submit.prevent="updateSigninImage">
                            <div class="mb-2">
                               <input type="file" wire:model="signin_image" id="signin_image" class="form-control" accept="image/*">
                                @error('signin_image') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="signin_image,updateSigninImage">Update Sign-in Image</span>
                                <span wire:loading wire:target="signin_image,updateSigninImage">Uploading...</span>
                            </button>
                        </form>
                    </div>
               </div>
            </div>
        </div>
    </div>
</div>
</div>
