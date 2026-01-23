<div>
    <div class="row pb-10">
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ $totalPosts }}</div>
                        <div class="font-14 text-secondary weight-500">Total Posts</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#00eccf">
                            <i class="icon-copy dw dw-file"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ $totalCategories }}</div>
                        <div class="font-14 text-secondary weight-500">Total Categories</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#ff5b5b">
                            <span class="icon-copy dw dw-folder"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ $totalTags }}</div>
                        <div class="font-14 text-secondary weight-500">Total Tags</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon">
                            <i class="icon-copy dw dw-price-tag" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ $totalUsers }}</div>
                        <div class="font-14 text-secondary weight-500">Total Users</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#09cc06">
                            <i class="icon-copy dw dw-user" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row pb-10">
        <div class="col-md-8 mb-20">
            <div class="card-box height-100-p pd-20">
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
                    <div class="h5 mb-md-0">Recent Activity</div>
                </div>
                <div class="user-list">
                    <ul>
                        @forelse($recentPosts as $post)
                            <li class="d-flex align-items-center justify-content-between">
                                <div class="name-avatar d-flex align-items-center pr-2">
                                    <div class="avatar mr-2 flex-shrink-0">
                                        <img src="{{ $post->user->picture }}" class="border-radius-100 box-shadow"
                                            width="50" height="50" alt="">
                                    </div>
                                    <div class="txt">
                                        <span class="badge badge-pill badge-sm" data-bgcolor="#e7ebf5"
                                            data-color="#265ed7">{{ $post->category->name }}</span>
                                        <div class="font-14 weight-600">{{ $post->title }}</div>
                                        <div class="font-12 weight-500" data-color="#b2b1b6">
                                            By {{ $post->user->name }} • {{ $post->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                                <div class="cta flex-shrink-0">
                                    <a href="{{ route('admin.posts.edit', $post->id) }}"
                                        class="btn btn-sm btn-outline-primary">Edit</a>
                                </div>
                            </li>
                        @empty
                            <li class="d-flex align-items-center justify-content-center">
                                <span class="text-muted">No recent activity</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-20">
            <div class="card-box min-height-200px pd-20 mb-20">
                <div class="h5 mb-20">Post Status</div>
                <div class="progress-box mb-15">
                    <p>Published Posts <span class="float-right">{{ $publishedPosts }}</span></p>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-success" role="progressbar"
                            style="width: {{ $totalPosts > 0 ? ($publishedPosts / $totalPosts) * 100 : 0 }}%"
                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="progress-box mb-15">
                    <p>Draft Posts <span class="float-right">{{ $draftPosts }}</span></p>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-warning" role="progressbar"
                            style="width: {{ $totalPosts > 0 ? ($draftPosts / $totalPosts) * 100 : 0 }}%"
                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <div class="card-box min-height-200px pd-20 mb-20">
                <div class="h5 mb-20">Quick Actions</div>
                <div class="d-flex flex-wrap">
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-block mb-2">
                        <i class="icon-copy dw dw-add"></i> Create New Post
                    </a>
                    <a href="{{ route('admin.categories') }}" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="icon-copy dw dw-folder"></i> Add Category
                    </a>
                    <a href="{{ route('admin.tags') }}" class="btn btn-outline-secondary btn-block mb-2">
                        <i class="icon-copy dw dw-price-tag"></i> Add Tag
                    </a>
                </div>
            </div>

            <div class="card-box min-height-200px pd-20">
                <div class="h5 mb-20">Popular Posts</div>
                <ul class="list-group list-group-flush">
                    @forelse($popularPosts as $post)
                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span class="text-truncate" style="max-width: 200px;" title="{{ $post->title }}">
                                {{ $post->title }}
                            </span>
                            <span class="badge badge-primary badge-pill">{{ $post->views }} <i
                                    class="icon-copy dw dw-eye"></i></span>
                        </li>
                    @empty
                        <li class="list-group-item px-0 text-muted">No popular posts yet</li>
                    @endforelse
                </ul>
            </div>

            <!-- System Status Widget -->
            <div class="card-box min-height-200px pd-20 mt-20"
                style="background: linear-gradient(45deg, #1b00ff, #001510); color: white;">
                <div class="h5 mb-20 text-white">System Status</div>
                <ul class="list-group list-group-flush" style="background: transparent;">
                    <li
                        class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-light">
                        <span class="text-white">Laravel Version</span>
                        <span class="text-white font-weight-bold">{{ $laravelVersion }}</span>
                    </li>
                    <li
                        class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-light">
                        <span class="text-white">Environment</span>
                        <span class="text-white font-weight-bold text-uppercase">{{ $appEnvironment }}</span>
                    </li>
                    <li
                        class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-light">
                        <span class="text-white">Database</span>
                        <span class="text-white font-weight-bold">{{ $databaseName }}</span>
                    </li>
                    <li
                        class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-light">
                        <span class="text-white">Cache Driver</span>
                        <span class="text-white font-weight-bold text-uppercase">{{ $cacheStatus }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>