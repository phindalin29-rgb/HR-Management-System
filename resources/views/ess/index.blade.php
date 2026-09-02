@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Employee Self Service</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">ESS</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <img src="{{ URL::to('/assets/images/'. ($user->avatar ?? 'user.jpg')) }}" class="avatar-lg rounded-circle" alt="">
                            <h4 class="mt-3">{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->position }} &middot; {{ $user->department }}</p>
                            <p class="text-muted">{{ $user->email }}</p>
                            <a href="{{ route('attendance/qrcode/page') }}" class="btn btn-info btn-block">My QR Attendance</a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Quick Actions</h4></div>
                        <div class="card-body">
                            <a href="{{ route('form/leaves/employee/new') }}" class="btn btn-primary btn-block mb-2">Request Leave</a>
                            <a href="{{ route('attendance/employee/page') }}" class="btn btn-secondary btn-block mb-2">My Attendance</a>
                            <a href="{{ route('calendar/page') }}" class="btn btn-light btn-block">View Calendar</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card dash-widget">
                                <div class="card-body text-center">
                                    <h3>{{ $summary['present'] }}</h3><span>Days Present</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card dash-widget">
                                <div class="card-body text-center">
                                    <h3>{{ $summary['late'] }}</h3><span>Days Late</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card dash-widget">
                                <div class="card-body text-center">
                                    <h3>{{ $summary['half_day'] }}</h3><span>Half Days</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Today's Attendance</h4>
                        </div>
                        <div class="card-body">
                            @if($today && $today->check_in)
                                <p>Checked in at <strong>{{ \Carbon\Carbon::parse($today->check_in)->format('h:i A') }}</strong>
                                    @if($today->method) <span class="badge bg-info-light">{{ ucfirst($today->method) }}</span> @endif
                                </p>
                                @if($today->check_out)
                                    <p>Checked out at <strong>{{ \Carbon\Carbon::parse($today->check_out)->format('h:i A') }}</strong></p>
                                @endif
                            @else
                                <p class="text-muted">Not checked in today.</p>
                                <form method="POST" action="{{ route('attendance/checkin') }}">
                                    @csrf
                                    <button class="btn btn-success">Check In</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h4 class="card-title">My Recent Leaves</h4></div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table mb-0">
                                    <thead><tr><th>Type</th><th>From</th><th>To</th><th>Status</th></tr></thead>
                                    <tbody>
                                        @forelse($leaves as $leave)
                                            <tr>
                                                <td>{{ $leave->leave_type }}</td>
                                                <td>{{ $leave->date_from }}</td>
                                                <td>{{ $leave->date_to }}</td>
                                                <td>
                                                    @if($leave->status == 'Approved')<span class="badge bg-success-light">Approved</span>
                                                    @elseif($leave->status == 'Rejected')<span class="badge bg-danger-light">Rejected</span>
                                                    @else<span class="badge bg-warning-light">Pending</span>@endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4" class="text-center">No leave requests.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Notifications</h4></div>
                        <div class="card-body">
                            @forelse($notifications as $note)
                                <div class="alert alert-{{ $note->channel == 'email' ? 'info' : 'warning' }} py-2">
                                    <strong>{{ strtoupper($note->channel) }}:</strong> {{ $note->body }}
                                </div>
                            @empty
                                <p class="text-muted">No notifications.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

@endsection
