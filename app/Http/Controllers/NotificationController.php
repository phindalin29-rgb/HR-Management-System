<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderByDesc('created_at')->paginate(20);

        return view('settings.notifications', compact('notifications'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'channel'   => 'required|in:email,sms',
            'recipient' => 'required|string',
            'subject'   => 'nullable|string|max:255',
            'body'      => 'required|string',
        ]);

        $notification = Notification::create([
            'channel'    => $request->channel,
            'recipient'  => $request->recipient,
            'subject'    => $request->subject,
            'body'       => $request->body,
            'status'     => 'queued',
            'created_by' => session('user_id'),
        ]);

        $sent = false;

        if ($request->channel === 'email') {
            try {
                Mail::raw($request->body, function ($message) use ($request, $notification) {
                    $message->to($request->recipient)
                        ->subject($request->subject ?: 'Notification from HR System');
                });
                $sent = true;
            } catch (\Exception $e) {
                \Log::error('Email send failed: ' . $e->getMessage());
            }
        } else {
            $sent = app(SmsService::class)->send($request->recipient, $request->body);
        }

        $notification->update([
            'status'  => $sent ? 'sent' : 'failed',
            'sent_at' => $sent ? now() : null,
        ]);

        flash()->success($sent ? 'Notification sent successfully :)' : 'Failed to send notification :(');

        return redirect()->back();
    }
}
