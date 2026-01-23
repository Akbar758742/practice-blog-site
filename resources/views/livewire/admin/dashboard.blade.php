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

    <!-- Mini Stats & Health Warnings -->
    <div class="row pb-10">
        <!-- Today/This Week Metrics -->
        <div class="col-md-6 mb-20">
            <div class="card-box height-100-p pd-20">
                <div class="h5 mb-20">Momentum Metrics</div>
                <div class="row">
                    <div class="col-4 text-center">
                        <div class="h3 mb-0 text-primary">{{ $postsToday }}</div>
                        <div class="font-12 text-secondary">Created Today</div>
                    </div>
                    <div class="col-4 text-center border-left border-right">
                        <div class="h3 mb-0 text-success">{{ $postsThisWeek }}</div>
                        <div class="font-12 text-secondary">Published This Week</div>
                    </div>
                    <div class="col-4 text-center">
                        <div class="h3 mb-0 text-info">{{ $newUsersThisWeek }}</div>
                        <div class="font-12 text-secondary">New Users This Week</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content Health -->
        <div class="col-md-6 mb-20">
            <div class="card-box height-100-p pd-20">
                <div class="h5 mb-20">Content Health</div>
                <ul class="list-group list-group-flush">
                    @if($postsWithoutCategory > 0)
                    <li class="list-group-item d-flex justify-content-between align-items-center text-danger px-0 py-1 border-0">
                        <span><i class="icon-copy dw dw-warning mr-2"></i> Posts without category</span>
                        <span class="badge badge-danger badge-pill">{{ $postsWithoutCategory }}</span>
                    </li>
                    @endif
                    @if($postsWithoutImage > 0)
                    <li class="list-group-item d-flex justify-content-between align-items-center text-warning px-0 py-1 border-0">
                        <span><i class="icon-copy dw dw-image mr-2"></i> Posts without featured image</span>
                        <span class="badge badge-warning badge-pill">{{ $postsWithoutImage }}</span>
                    </li>
                    @endif
                     @if($oldDrafts > 0)
                    <li class="list-group-item d-flex justify-content-between align-items-center text-secondary px-0 py-1 border-0">
                        <span><i class="icon-copy dw dw-time mr-2"></i> Drafts older than 30 days</span>
                        <span class="badge badge-secondary badge-pill">{{ $oldDrafts }}</span>
                    </li>
                    @endif
                    @if($postsNeedingUpdate > 0)
                    <li class="list-group-item d-flex justify-content-between align-items-center text-info px-0 py-1 border-0">
                        <span><i class="icon-copy dw dw-refresh mr-2"></i> Posts needing update (6m+)</span>
                        <span class="badge badge-info badge-pill">{{ $postsNeedingUpdate }}</span>
                    </li>
                    @endif
                    
                    @if($postsWithoutCategory == 0 && $postsWithoutImage == 0 && $oldDrafts == 0 && $postsNeedingUpdate == 0)
                        <li class="list-group-item text-success px-0 border-0">
                            <i class="icon-copy dw dw-checked mr-2"></i> All content looks healthy!
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    <div class="row pb-10">
        <div class="col-md-8 mb-20">
            <!-- Analytics Placeholder -->
            <div class="card-box pd-20 mb-20 border-left-4 border-indigo-500" style="background: repeating-linear-gradient(45deg,#f9f9f9,#f9f9f9 10px,#ffffff 10px,#ffffff 20px);">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                         <div class="h5 mb-1">Traffic Analytics</div>
                         <div class="font-14 text-secondary">Detailed insights coming soon via Google Analytics 4 integration.</div>
                    </div>
                     <div class="font-30 text-gray-300">
                        <i class="icon-copy dw dw-analytics-21"></i>
                     </div>
                </div>
            </div>

            <div class="card-box height-100-p pd-20">
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3">
                    <div class="h5 mb-md-0">Recent Activity</div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn {{ $activeFilter === 'latest' ? 'btn-primary' : 'btn-outline-primary' }}" wire:click="setFilter('latest')">Latest</button>
                        <button type="button" class="btn {{ $activeFilter === 'published' ? 'btn-success' : 'btn-outline-success' }}" wire:click="setFilter('published')">Published</button>
                        <button type="button" class="btn {{ $activeFilter === 'drafts' ? 'btn-warning' : 'btn-outline-warning' }}" wire:click="setFilter('drafts')">Drafts</button>
                         <button type="button" class="btn {{ $activeFilter === 'scheduled' ? 'btn-info' : 'btn-outline-info' }}" wire:click="setFilter('scheduled')">Scheduled</button>
                    </div>
                </div>
                <div class="user-list">
                    <ul>
                        @forelse($recentPosts as $post)
                            <li class="d-flex align-items-center justify-content-between">
                                <div class="name-avatar d-flex align-items-center pr-2">
                                    <div class="avatar mr-2 flex-shrink-0">
                                        @if($post->user)
                                            <img src="{{ $post->user->picture }}" class="border-radius-100 box-shadow" width="50" height="50" alt="">
                                        @else
                                            <div class="width-50 height-50 bg-gray-200 border-radius-100 d-flex align-items-center justify-content-center text-gray-500">
                                                <i class="dw dw-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="txt">
                                        @if($post->category)
                                            <span class="badge badge-pill badge-sm" data-bgcolor="#e7ebf5" data-color="#265ed7">{{ $post->category->name }}</span>
                                        @endif
                                        <div class="font-14 weight-600">{{ $post->title }}</div>
                                        <div class="font-12 weight-500" data-color="#b2b1b6">
                                            By {{ $post->user ? $post->user->name : 'Unknown' }} • {{ $post->created_at ? $post->created_at->diffForHumans() : '' }}
                                            @if($post->is_published)
                                                <span class="text-success ml-1">• Published</span>
                                            @else
                                                <span class="text-warning ml-1">• Draft</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="cta flex-shrink-0">
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                </div>
                            </li>
                        @empty
                            <li class="d-flex align-items-center justify-content-center py-4">
                                <span class="text-muted">No posts found for this filter.</span>
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
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalPosts > 0 ? ($publishedPosts / $totalPosts) * 100 : 0 }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="progress-box mb-15">
                     <p>Draft Posts <span class="float-right">{{ $draftPosts }}</span></p>
                     <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalPosts > 0 ? ($draftPosts / $totalPosts) * 100 : 0 }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
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
                        <span class="badge badge-primary badge-pill">{{ $post->views }} <i class="icon-copy dw dw-eye"></i></span>
                    </li>
                    @empty
                    <li class="list-group-item px-0 text-muted">No popular posts yet</li>
                    @endforelse
                </ul>
             </div>

            <!-- System Status Widget -->
            <div class="card-box min-height-200px pd-20 mt-20" style="background: linear-gradient(45deg, #1b00ff, #001510); color: white;">
                <div class="h5 mb-20 text-white">System Status</div>
                <ul class="list-group list-group-flush" style="background: transparent;">
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-light">
                        <span class="text-white">Laravel Version</span>
                        <span class="text-white font-weight-bold">{{ $laravelVersion }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-light">
                        <span class="text-white">Environment</span>
                        <span class="text-white font-weight-bold text-uppercase">{{ $appEnvironment }}</span>
                    </li>
                     <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-light">
                        <span class="text-white">Database</span>
                        <span class="text-white font-weight-bold">{{ $databaseName }}</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent border-light">
                        <span class="text-white">Cache Driver</span>
                        <span class="text-white font-weight-bold text-uppercase">{{ $cacheStatus }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>