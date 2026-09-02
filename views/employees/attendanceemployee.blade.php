@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Attendance (Employee)</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Today's Check In / Check Out -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-1">Today, {{ now()->format('l, d M Y') }}</h4>
                                    @if($today && $today->check_in)
                                        <p class="mb-0">Checked in at <strong>{{ \Carbon\Carbon::parse($today->check_in)->format('h:i A') }}</strong>
                                            @if($today->status == 'Late') <span class="badge bg-warning-light">Late</span> @endif
                                        </p>
                                        @if($today->check_out)
                                            <p class="mb-0">Checked out at <strong>{{ \Carbon\Carbon::parse($today->check_out)->format('h:i A') }}</strong>
                                                @if($today->work_hours) (worked {{ $today->work_hours }}) @endif
                                            </p>
                                        @endif
                                    @else
                                        <p class="mb-0 text-muted">You have not checked in yet today.</p>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-right">
                                    @if(!$today || !$today->check_in)
                                        <form method="POST" action="{{ route('attendance/checkin') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success"><i class="fa fa-sign-in"></i> Check In</button>
                                        </form>
                                    @elseif(!$today->check_out)
                                        <form method="POST" action="{{ route('attendance/checkout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-sign-out"></i> Check Out</button>
                                        </form>
                                    @else
                                        <span class="badge bg-success-light" style="font-size:14px;">Completed for today</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Today's Check In / Check Out -->

            <!-- Monthly Summary -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card dash-widget">
                        <div class="card-body text-center">
                            <h3>{{ $summary['present'] }}</h3>
                            <span>Days Present This Month</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dash-widget">
                        <div class="card-body text-center">
                            <h3>{{ $summary['late'] }}</h3>
                            <span>Days Late</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card dash-widget">
                        <div class="card-body text-center">
                            <h3>{{ $summary['half_day'] }}</h3>
                            <span>Half Days</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Monthly Summary -->

            <!-- Search Filter -->
            <form method="GET" action="{{ route('attendance/employee/page') }}">
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <select name="month" class="select floating" style="width:100%">
                                @foreach(range(1,12) as $m)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                                @endforeach
                            </select>
                            <label class="focus-label">Select Month</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <select name="year" class="select floating" style="width:100%">
                                @foreach(range(now()->year, now()->year - 4) as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                            <label class="focus-label">Select Year</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <button type="submit" class="btn btn-success btn-block"> Search </button>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->

            <!-- History -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Work Hours</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $rec)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($rec->date)->format('d M Y') }}</td>
                                        <td>{{ $rec->check_in ? \Carbon\Carbon::parse($rec->check_in)->format('h:i A') : '-' }}</td>
                                        <td>{{ $rec->check_out ? \Carbon\Carbon::parse($rec->check_out)->format('h:i A') : '-' }}</td>
                                        <td>{{ $rec->work_hours ?? '-' }}</td>
                                        <td>
                                            @if($rec->status == 'Present')
                                                <span class="badge bg-success-light">Present</span>
                                            @elseif($rec->status == 'Late')
                                                <span class="badge bg-warning-light">Late</span>
                                            @elseif($rec->status == 'Half Day')
                                                <span class="badge bg-info-light">Half Day</span>
                                            @else
                                                <span class="badge bg-danger-light">{{ $rec->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No attendance records for this month.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /History -->

        </div>
    </div>
    <!-- /Page Wrapper -->

@endsection
