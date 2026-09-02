@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">QR Code Attendance</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">QR Attendance</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->user_id }}</p>

                            <div id="qr-code" class="d-inline-block my-3"></div>

                            <p class="text-muted small">
                                Scan this code with the attendance kiosk / mobile camera to check in.
                            </p>

                            <div class="mt-3">
                                @if(!$today || !$today->check_in)
                                    <form method="POST" action="{{ route('attendance/checkin') }}">
                                        @csrf
                                        <input type="hidden" name="method" value="qr">
                                        <button class="btn btn-success">Check In (Manual)</button>
                                    </form>
                                @else
                                    <span class="badge bg-success-light">Already checked in at {{ \Carbon\Carbon::parse($today->check_in)->format('h:i A') }}</span>
                                @endif
                            </div>

                            <div class="mt-3">
                                <a href="{{ $scanUrl }}" target="_blank" class="btn btn-outline-info btn-sm">Open Scan Page (demo)</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var scanUrl = @json($scanUrl);
        if (scanUrl && typeof QRCode !== 'undefined') {
            new QRCode(document.getElementById('qr-code'), {
                text: scanUrl,
                width: 220,
                height: 220
            });
        }
    });
</script>
@endsection
