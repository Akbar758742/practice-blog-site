<div>
    {{-- Statistics Cards --}}
    <div class="row pb-10">
        <div class="col-xl-2 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ number_format($stats['total']) }}</div>
                        <div class="font-14 text-secondary weight-500">Total</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#00eccf"><i class="icon-copy dw dw-list3"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ number_format($stats['today']) }}</div>
                        <div class="font-14 text-secondary weight-500">Today</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#09cc06"><i class="icon-copy fa fa-calendar-check-o"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ number_format($stats['this_week']) }}</div>
                        <div class="font-14 text-secondary weight-500">This Week</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#5b93ff"><i class="icon-copy fa fa-calendar"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ number_format($stats['this_month']) }}</div>
                        <div class="font-14 text-secondary weight-500">This Month</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#5b93ff"><i class="icon-copy fa fa-calendar-o"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3 {{ $stats['critical'] > 0 ? 'bg-light-danger' : '' }}">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-danger">{{ number_format($stats['critical']) }}</div>
                        <div class="font-14 text-secondary weight-500">Critical</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#ff5b5b"><i class="icon-copy dw dw-warning"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-3 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3 {{ $stats['warning'] > 0 ? 'bg-light-warning' : '' }}">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-warning">{{ number_format($stats['warning']) }}</div>
                        <div class="font-14 text-secondary weight-500">Warnings</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" data-color="#ffaf00"><i class="icon-copy dw dw-alert"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="pd-20 card-box mb-30">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h5 class="text-blue">
                    <i class="icon-copy fa fa-filter"></i> Filters
                </h5>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-2">
                <input type="text" class="form-control" placeholder="Search description..." wire:model.live.debounce.300ms="search">
            </div>
            <div class="col-md-2 mb-2">
                <select class="form-control" wire:model.live="filterUser">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <select class="form-control" wire:model.live="filterLogName">
                    <option value="">All Types</option>
                    @foreach($logNames as $logName)
                        <option value="{{ $logName }}">{{ ucfirst($logName) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <select class="form-control" wire:model.live="filterAction">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}">{{ ucfirst(str_replace('_', ' ', $action)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <select class="form-control" wire:model.live="filterSeverity">
                    <option value="">All Severities</option>
                    @foreach($severities as $severity)
                        <option value="{{ $severity }}" class="{{ $severity === 'CRITICAL' ? 'text-danger' : ($severity === 'WARNING' ? 'text-warning' : '') }}">
                            {{ $severity }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1 mb-2">
                <button wire:click="clearFilters" class="btn btn-secondary btn-sm">
                    <i class="icon-copy fa fa-refresh"></i>
                </button>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-3 mb-2">
                <label class="small text-muted">From Date</label>
                <input type="date" class="form-control" wire:model.live="filterDateFrom">
            </div>
            <div class="col-md-3 mb-2">
                <label class="small text-muted">To Date</label>
                <input type="date" class="form-control" wire:model.live="filterDateTo">
            </div>
            <div class="col-md-2 mb-2">
                <label class="small text-muted">Per Page</label>
                <select class="form-control" wire:model.live="perPage">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Activity Log Table --}}
    <div class="pd-20 card-box mb-30">
        <div class="clearfix mb-20">
            <div class="pull-left">
                <h4 class="text-blue h4">
                    <i class="icon-copy fa fa-history"></i> Activity Log
                </h4>
                <p class="text-muted font-14">Track all system activities - who did what, when</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th width="150">Date/Time</th>
                        <th width="120">User</th>
                        <th width="80">Type</th>
                        <th width="100">Action</th>
                        <th width="80">Severity</th>
                        <th>Description</th>
                        <th width="200">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                        @php
                            $properties = $activity->properties ? $activity->properties->toArray() : [];
                            $action = $properties['action'] ?? 'unknown';
                            $severity = $properties['severity'] ?? 'INFO';
                            $flag = $properties['flag'] ?? null;
                            $actionColor = match($action) {
                                'created' => 'success',
                                'updated' => 'primary',
                                'deleted' => 'warning',
                                'restored' => 'info',
                                'force_deleted' => 'danger',
                                'status_changed' => 'secondary',
                                'bulk_delete' => 'danger',
                                'unauthorized_attempt' => 'danger',
                                'role_changed' => 'warning',
                                'login_failure' => 'danger',
                                'permission_denied' => 'warning',
                                default => 'light',
                            };
                            $severityColor = match($severity) {
                                'CRITICAL' => 'danger',
                                'WARNING' => 'warning',
                                'INFO' => 'info',
                                default => 'secondary',
                            };
                        @endphp
                        <tr class="{{ $severity === 'CRITICAL' ? 'table-danger' : ($severity === 'WARNING' ? 'table-warning' : '') }}">
                            <td>
                                <div class="font-weight-bold">{{ $activity->created_at->format('d M Y') }}</div>
                                <small class="text-muted">{{ $activity->created_at->format('H:i:s') }}</small>
                            </td>
                            <td>
                                @if($activity->causer)
                                    <span class="badge badge-pill badge-light">
                                        <i class="icon-copy fa fa-user"></i> {{ $activity->causer->name }}
                                    </span>
                                @else
                                    <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-pill badge-info">{{ ucfirst($activity->log_name) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-{{ $actionColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $action)) }}
                                </span>
                                @if($flag)
                                    <br><small class="badge badge-outline-{{ $actionColor }} mt-1">{{ $flag }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $severityColor }}">
                                    @if($severity === 'CRITICAL')
                                        <i class="icon-copy dw dw-warning mr-1"></i>
                                    @elseif($severity === 'WARNING')
                                        <i class="icon-copy dw dw-alert mr-1"></i>
                                    @else
                                        <i class="icon-copy dw dw-information mr-1"></i>
                                    @endif
                                    {{ $severity }}
                                </span>
                            </td>
                            <td>
                                {{ $activity->description }}
                            </td>
                            <td>
                                @if(!empty($properties))
                                    @if(isset($properties['changes']) && is_array($properties['changes']))
                                        <button type="button" class="btn btn-xs btn-outline-primary" data-toggle="modal" data-target="#detailModal{{ $activity->id }}">
                                            <i class="icon-copy fa fa-eye"></i> View Changes
                                        </button>
                                    @elseif(isset($properties['post_count']) || isset($properties['comment_count']))
                                        <small class="text-muted">
                                            Posts: {{ $properties['post_count'] ?? 0 }},
                                            Comments: {{ $properties['comment_count'] ?? 0 }}
                                        </small>
                                    @elseif(isset($properties['field']))
                                        <small>
                                            {{ $properties['field'] }}:
                                            <span class="text-danger">{{ $properties['old_value'] ?? '-' }}</span>
                                            →
                                            <span class="text-success">{{ $properties['new_value'] ?? '-' }}</span>
                                        </small>
                                    @else
                                        <button type="button" class="btn btn-xs btn-outline-secondary" data-toggle="modal" data-target="#detailModal{{ $activity->id }}">
                                            <i class="icon-copy fa fa-info-circle"></i> Details
                                        </button>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Detail Modal --}}
                        @if(!empty($properties))
                        <div class="modal fade" id="detailModal{{ $activity->id }}" tabindex="-1" role="dialog">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Activity Details</h5>
                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Log:</strong> {{ ucfirst($activity->log_name) }}</p>
                                                <p><strong>Action:</strong> {{ ucfirst(str_replace('_', ' ', $action)) }}</p>
                                                <p><strong>User:</strong> {{ $activity->causer?->name ?? 'System' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Date:</strong> {{ $activity->created_at->format('d M Y H:i:s') }}</p>
                                                <p><strong>Description:</strong> {{ $activity->description }}</p>
                                            </div>
                                        </div>
                                        <hr>
                                        <h6>Properties:</h6>
                                        <pre class="bg-light p-3" style="max-height: 300px; overflow-y: auto;">{{ json_encode($properties, JSON_PRETTY_PRINT) }}</pre>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="icon-copy fa fa-inbox fa-3x mb-3 d-block"></i>
                                    No activity logs found.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Professional Pagination --}}
        <x-pagination :items="$activities" wirePath="gotoPage" />
    </div>
</div>
