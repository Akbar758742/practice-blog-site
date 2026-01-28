<div>
    <div class="min-height-200px">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>All Notifications</h4>
                    </div>
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Notifications
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row pb-10">
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">{{ $stats['total'] }}</div>
                            <div class="font-14 text-secondary weight-500">Total Notifications</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#00eccf">
                                <i class="icon-copy dw dw-notification"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">{{ $stats['unread'] }}</div>
                            <div class="font-14 text-secondary weight-500">Unread</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#09cc06">
                                <i class="icon-copy dw dw-inbox1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-danger">{{ $stats['critical'] }}</div>
                            <div class="font-14 text-secondary weight-500">Critical</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#ff5b5b">
                                <i class="icon-copy dw dw-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-warning">{{ $stats['warning'] }}</div>
                            <div class="font-14 text-secondary weight-500">Warnings</div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon" data-color="#ffaf00">
                                <i class="icon-copy dw dw-alert"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card-box mb-30">
            <div class="pd-20">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Type</label>
                            <select wire:model.live="filterType" class="form-control">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucwords(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Severity</label>
                            <select wire:model.live="filterSeverity" class="form-control">
                                <option value="">All Severities</option>
                                <option value="CRITICAL">Critical</option>
                                <option value="WARNING">Warning</option>
                                <option value="INFO">Info</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select wire:model.live="filterStatus" class="form-control">
                                <option value="">All</option>
                                <option value="unread">Unread</option>
                                <option value="read">Read</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button wire:click="clearFilters" class="btn btn-secondary mr-2">
                                    <i class="icon-copy dw dw-refresh"></i> Clear
                                </button>
                                @if($stats['unread'] > 0)
                                    <button wire:click="markAllAsRead" class="btn btn-primary mr-2">
                                        <i class="icon-copy dw dw-check"></i> Mark All Read
                                    </button>
                                @endif
                                @if($stats['total'] > 0)
                                    <button wire:click="clearAll" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete all notifications?')">
                                        <i class="icon-copy dw dw-trash"></i> Clear All
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="card-box mb-30">
            <div class="pd-20">
                @forelse($notifications as $notification)
                    @php
                        $data = $notification->data;
                    @endphp
                    <div class="notification-card d-flex align-items-start p-3 mb-2 rounded {{ is_null($notification->read_at) ? 'bg-light-primary border-left-primary' : 'bg-white' }}"
                         style="border: 1px solid #e4e6eb; {{ is_null($notification->read_at) ? 'border-left: 4px solid #1b00ff;' : '' }}">
                        <div class="notification-icon mr-3">
                            <span class="icon-circle {{ $this->getColorClass($data['color'] ?? 'primary') }} text-white rounded-circle d-flex align-items-center justify-content-center"
                                  style="width: 50px; height: 50px; min-width: 50px;">
                                <i class="icon-copy {{ $data['icon'] ?? 'dw dw-notification' }}" style="font-size: 20px;"></i>
                            </span>
                        </div>
                        <div class="notification-content flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 font-weight-bold text-dark">
                                    {{ $data['title'] ?? 'Notification' }}
                                </h6>
                                <div class="d-flex align-items-center">
                                    <span class="badge {{ $this->getSeverityBadgeClass($data['severity'] ?? 'INFO') }} mr-2">
                                        {{ $data['severity'] ?? 'INFO' }}
                                    </span>
                                    @if(is_null($notification->read_at))
                                        <span class="badge badge-primary">Unread</span>
                                    @endif
                                </div>
                            </div>
                            <p class="mb-2 text-muted">{{ $data['message'] ?? '' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="icon-copy dw dw-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                </small>
                                <div class="btn-group btn-group-sm">
                                    @if(isset($data['url']))
                                        <a href="{{ $data['url'] }}" wire:click="markAsRead('{{ $notification->id }}')" class="btn btn-outline-primary btn-sm">
                                            <i class="icon-copy dw dw-eye"></i> View
                                        </a>
                                    @endif
                                    @if(is_null($notification->read_at))
                                        <button wire:click="markAsRead('{{ $notification->id }}')" class="btn btn-outline-success btn-sm">
                                            <i class="icon-copy dw dw-check"></i> Mark Read
                                        </button>
                                    @endif
                                    <button wire:click="deleteNotification('{{ $notification->id }}')" class="btn btn-outline-danger btn-sm">
                                        <i class="icon-copy dw dw-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="icon-copy dw dw-notification mb-3" style="font-size: 48px; color: #ccc;"></i>
                        <h5 class="text-muted">No notifications found</h5>
                        <p class="text-muted">You're all caught up!</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="pd-20 pt-0">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
