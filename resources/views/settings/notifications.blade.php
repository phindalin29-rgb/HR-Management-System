@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <h3 class="page-title">Notifications</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Notifications</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Send Notification</h4>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('notifications/send') }}">
                                @csrf
                                <div class="form-group">
                                    <label>Channel</label>
                                    <select name="channel" class="form-control" required>
                                        <option value="email">Email</option>
                                        <option value="sms">SMS</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Recipient (email or phone)</label>
                                    <input type="text" name="recipient" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input type="text" name="subject" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Message</label>
                                    <textarea name="body" class="form-control" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">History</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped custom-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Channel</th>
                                            <th>Recipient</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Sent At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($notifications as $note)
                                            <tr>
                                                <td>{{ strtoupper($note->channel) }}</td>
                                                <td>{{ $note->recipient }}</td>
                                                <td>{{ $note->subject ?? '-' }}</td>
                                                <td>
                                                    @if($note->status == 'sent')
                                                        <span class="badge bg-success-light">Sent</span>
                                                    @elseif($note->status == 'failed')
                                                        <span class="badge bg-danger-light">Failed</span>
                                                    @else
                                                        <span class="badge bg-warning-light">Queued</span>
                                                    @endif
                                                </td>
                                                <td>{{ $note->sent_at ? $note->sent_at->format('d M Y H:i') : '-' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No notifications sent yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Page Wrapper -->

@endsection
