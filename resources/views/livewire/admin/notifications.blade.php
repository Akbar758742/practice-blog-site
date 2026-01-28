<div class="user-notification" wire:poll.30s="loadNotifications">
    <style>
        .notification-dropdown {
            min-width: 350px;
            max-width: 400px;
            padding: 0;
        }
        .notification-dropdown .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }
        .notification-dropdown .notification-item {
            transition: background-color 0.2s;
        }
        .notification-dropdown .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-dropdown .notification-item.unread {
            background-color: #e8f4fd;
            border-left: 3px solid #1b00ff;
        }
        .notification-dropdown .icon-circle {
            font-size: 14px;
        }
        .notification-dropdown .badge-sm {
            font-size: 10px;
            padding: 2px 5px;
        }
        .bg-light-primary {
            background-color: #e8f4fd !important;
        }
    </style>
    <div class="dropdown">
        <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
            <i class="icon-copy dw dw-notification"></i>
            @if($unreadCount > 0)
                <span class="badge notification-active">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-right notification-dropdown">
            <div class="notification-header d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                <h6 class="mb-0 font-weight-bold">Notifications</h6>
                <div class="d-flex align-items-center">
                    @if($unreadCount > 0)
                        <button wire:click="markAllAsRead" class="btn btn-link btn-sm text-primary p-0 mr-2" title="Mark all as read">
                            <i class="icon-copy dw dw-check"></i>
                        </button>
                    @endif
                    @if(count($notifications) > 0)
                        <button wire:click="clearAll" class="btn btn-link btn-sm text-danger p-0" title="Clear all">
                            <i class="icon-copy dw dw-trash"></i>
                        </button>
                    @endif
                </div>
            </div>
            <div class="notification-list mx-h-350 customscroll">
                <ul class="list-unstyled mb-0">
                    @forelse($notifications as $notification)
                        <li class="notification-item {{ is_null($notification['read_at']) ? 'unread bg-light-primary' : '' }} border-bottom">
                            <a href="{{ $notification['url'] }}"
                               wire:click="markAsRead('{{ $notification['id'] }}')"
                               class="d-flex align-items-start p-3 text-decoration-none">
                                <div class="notification-icon mr-3">
                                    <span class="icon-circle bg-{{ $notification['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center"
                                          style="width: 40px; height: 40px;">
                                        <i class="icon-copy {{ $notification['icon'] }}"></i>
                                    </span>
                                </div>
                                <div class="notification-content flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 13px;">
                                            {{ $notification['title'] }}
                                        </h6>
                                        <span class="badge {{ $this->getSeverityBadgeClass($notification['severity']) }} badge-sm ml-2">
                                            {{ $notification['severity'] }}
                                        </span>
                                    </div>
                                    <p class="mb-1 text-muted" style="font-size: 12px; line-height: 1.4;">
                                        {{ \Illuminate\Support\Str::limit($notification['message'], 80) }}
                                    </p>
                                    <small class="text-muted">{{ $notification['created_at'] }}</small>
                                </div>
                                @if(is_null($notification['read_at']))
                                    <span class="unread-indicator ml-2">
                                        <i class="fa fa-circle text-primary" style="font-size: 8px;"></i>
                                    </span>
                                @endif
                            </a>
                        </li>
                    @empty
                        <li class="text-center py-4 text-muted">
                            <i class="icon-copy dw dw-notification mb-2" style="font-size: 24px;"></i>
                            <p class="mb-0">No notifications yet</p>
                        </li>
                    @endforelse
                </ul>
            </div>
            @if(count($notifications) > 0)
                <div class="notification-footer text-center py-2 border-top">
                    <a href="{{ route('admin.all-notifications') }}" class="text-primary font-weight-bold">
                        View All Notifications
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
