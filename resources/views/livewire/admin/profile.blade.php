<div>
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
            <div class="pd-20 card-box height-100-p">
                <div class="profile-photo">
                    <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('profilePicturefile').click();" class="edit-avatar"><i class="fa fa-pencil"></i></a>
                    <img src="{{ $user->picture }}" alt="" id="profilePicturePreview" class="avatar-photo">
                    <input type="file" name="profilePicturefile" id="profilePicturefile" style="display: none"
                      >

                </div>
                <h5 class="text-center h5 mb-0">{{ $user->name }}</h5>
                <p class="text-center text-muted font-14">
                    {{ $user->email }}
                </p>

                <div class="profile-social">
                    <h5 class="mb-20 h5 text-blue">Social Links</h5>
                    <ul class="clearfix">
                        <li>
                            <a href="#" class="btn" data-bgcolor="#3b5998" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(59, 89, 152);"><i
                                    class="fa fa-facebook"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn" data-bgcolor="#1da1f2" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(29, 161, 242);"><i
                                    class="fa fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn" data-bgcolor="#007bb5" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(0, 123, 181);"><i
                                    class="fa fa-linkedin"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn" data-bgcolor="#f46f30" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(244, 111, 48);"><i
                                    class="fa fa-instagram"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn" data-bgcolor="#c32361" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(195, 35, 97);"><i
                                    class="fa fa-dribbble"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn" data-bgcolor="#3d464d" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(61, 70, 77);"><i
                                    class="fa fa-dropbox"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn" data-bgcolor="#db4437" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(219, 68, 55);"><i
                                    class="fa fa-google-plus"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn" data-bgcolor="#bd081c" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(189, 8, 28);"><i
                                    class="fa fa-pinterest-p"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn" data-bgcolor="#00aff0" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(0, 175, 240);"><i
                                    class="fa fa-skype"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn" data-bgcolor="#00b489" data-color="#ffffff"
                                style="color: rgb(255, 255, 255); background-color: rgb(0, 180, 137);"><i
                                    class="fa fa-vine"></i></a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
            <div class="card-box height-100-p overflow-hidden">
                <div class="profile-tab height-100-p">
                    <div class="tab height-100-p">
                        <ul class="nav nav-tabs customtab" role="tablist">
                            <li class="nav-item">
                                <a wire:click="selectTab('personal_details')"
                                    class="nav-link {{ $tab == 'personal_details' ? 'active' : '' }}" data-toggle="tab"
                                    href="#personal_details" role="tab">personal details</a>
                            </li>
                            <li class="nav-item">
                                <a wire:click="selectTab('update_password')"
                                    class="nav-link {{ $tab == 'update_password' ? 'active' : '' }}" data-toggle="tab"
                                    href="#update_password" role="tab">update
                                    password</a>
                            </li>
                            <li class="nav-item">
                                <a wire:click="selectTab('social_links')"
                                    class="nav-link {{ $tab == 'social_links' ? 'active' : '' }}" data-toggle="tab"
                                    href="#social_links" role="tab">social_links</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Personal Details Tab start -->
                            <div class="tab-pane fade {{ $tab == 'personal_details' ? 'show active' : '' }}"
                                id="personal_details" role="tabpanel">
                                <div class="pd-20">
                                    <form wire:submit="updateProfile()">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Full Name</label>
                                                    <input type="text" wire:model="name" class="form-control"
                                                        value="{{ $user->name }}" />
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" wire:model="email" class="form-control"
                                                        value="{{ $user->email }}" disabled />
                                                    @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" wire:model="phone" class="form-control" value="{{ $user->phone }}" />
                                                @error('phone')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div> --}}
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>bio</label>
                                                    <textarea type="text" wire:model="bio" class="form-control" cols="4" rows="4"></textarea>
                                                    @error('bio')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="button" wire:click="updateProfile()"
                                                        class="btn btn-primary">Update
                                                        Profile</button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Personal Details Tab End -->
                            <!-- Update Password Tab start -->
                            <div class="tab-pane fade {{ $tab == 'update_password' ? 'show active' : '' }}"
                                id="update_password" role="tabpanel">
                                <div class="pd-20 profile-task-wrap">
                                    ..update password content..
                                </div>
                            </div>
                            <!-- Update Password Tab End -->
                            <!-- Social Links Tab start -->
                            <div class="tab-pane fade {{ $tab == 'social_links' ? 'show active' : '' }}"
                                id="social_links" role="tabpanel">
                                <div class="pd-20 profile-task-wrap">
                                    ..social links content..
                                </div>
                            </div>
                            <!-- Social Links Tab End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>


 const cropper = new Kropify('#profilePicturefile', {
            aspectRatio: 1,
            preview: '#profilePicturePreview',
            processURL: '/profile/update', // or processURL:'/crop'
            allowedExtensions: ['jpg', 'jpeg', 'png'],
            showLoader: true,
            animationClass: 'pulse',
            // fileName: 'avatar', // leave this commented if you want it to default to the input name
            cancelButtonText:'Cancel',
            maxWoH:500,
            onError: function (msg) {
                alert(msg);
                // toastr.error(msg);
            },
            onDone: function(response){
                alert(response.message);
                console.log(response.data);
                // toastr.success(response.message);
            }
        });
        // Preview Profile Picture
        // document.getElementById('profilePicturefile').addEventListener('change', function(event) {
        //     const [file] = event.target.files;
        //     if (file) {
        //         const reader = new FileReader();
        //         reader.onload = function(e) {
        //             document.getElementById('profilePicturePreview').src = e.target.result;
        //         }
        //         reader.readAsDataURL(file);
        //     }
        // });
    </script>
@endpush
