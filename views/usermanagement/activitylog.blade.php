@extends('layouts.master')
@section('content')
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Activity Log</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Activity Log</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <form method="GET" action="{{ route('activity/log') }}" class="form-inline">
                        <div class="form-group form-focus select-focus" style="width:100%">
                            <select name="module" class="select floating" onchange="this.form.submit()" style="width:100%">
                                <option value="">All Modules</option>
                                @foreach($modules as $m)
                                    <option value="{{ $m }}" {{ request('module')==$m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6 col-md-3">
                    <form method="GET" action="{{ route('activity/log') }}" class="form-inline">
                        <div class="form-group form-focus select-focus" style="width:100%">
                            <select name="action" class="select floating" onchange="this.form.submit()" style="width:100%">
                                <option value="">All Actions</option>
                                <option value="created" {{ request('action')=='created' ? 'selected' : '' }}>Created</option>
                                <option value="updated" {{ request('action')=='updated' ? 'selected' : '' }}>Updated</option>
                                <option value="deleted" {{ request('action')=='deleted' ? 'selected' : '' }}>Deleted</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Date / Time</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Module</th>
                                    <th>Record ID</th>
                                    <th>Description</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                        <td>{{ $log->user_name ?? 'System' }} <br><small class="text-muted">{{ $log->user_id }}</small></td>
                                        <td>
                                            @if($log->action == 'created')
                                                <span class="badge bg-success-light">Created</span>
                                            @elseif($log->action == 'updated')
                                                <span class="badge bg-warning-light">Updated</span>
                                            @else
                                                <span class="badge bg-danger-light">Deleted</span>
                                            @endif
                                        </td>
                                        <td>{{ $log->module }}</td>
                                        <td>#{{ $log->record_id }}</td>
                                        <td>{{ $log->description }}</td>
                                        <td>{{ $log->ip_address }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No activity recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="p-3">
                            {{ $logs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->
@endsection
