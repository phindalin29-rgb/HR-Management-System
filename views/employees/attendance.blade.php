@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Attendance (Admin)</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <form method="GET" action="{{ route('attendance/page') }}">
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

            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex mb-2">
                        <span class="mr-3"><i class="fa fa-check text-success"></i> Present</span>
                        <span class="mr-3"><i class="fa fa-clock-o text-warning"></i> Late</span>
                        <span class="mr-3"><i class="fa fa-adjust text-info"></i> Half Day</span>
                        <span class="mr-3"><i class="fa fa-close text-danger"></i> Absent / No Record</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped custom-table table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th style="min-width:180px;">Employee</th>
                                    @foreach(range(1, $daysInMonth) as $day)
                                        <th class="text-center">{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                    @php
                                        $employeeRecords = ($records[$employee->user_id] ?? collect())->keyBy(function ($r) {
                                            return \Carbon\Carbon::parse($r->date)->day;
                                        });
                                    @endphp
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a class="avatar avatar-xs" href="{{ url('employee/profile/'.$employee->id) }}"><img alt="" src="{{ URL::to('assets/img/profiles/avatar-09.jpg') }}"></a>
                                                <a href="{{ url('employee/profile/'.$employee->id) }}">{{ $employee->name }}</a>
                                            </h2>
                                        </td>
                                        @foreach(range(1, $daysInMonth) as $day)
                                            @php $rec = $employeeRecords->get($day); @endphp
                                            <td class="text-center">
                                                @if(!$rec)
                                                    <i class="fa fa-close text-danger" title="Absent / No Record"></i>
                                                @elseif($rec->status == 'Present')
                                                    <i class="fa fa-check text-success" title="Present {{ $rec->check_in }} - {{ $rec->check_out }}"></i>
                                                @elseif($rec->status == 'Late')
                                                    <i class="fa fa-clock-o text-warning" title="Late in at {{ $rec->check_in }}"></i>
                                                @elseif($rec->status == 'Half Day')
                                                    <i class="fa fa-adjust text-info" title="Half Day"></i>
                                                @else
                                                    <i class="fa fa-close text-danger" title="{{ $rec->status }}"></i>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $daysInMonth + 1 }}" class="text-center">No employees found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

@endsection
