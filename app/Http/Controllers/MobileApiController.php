<?php

namespace App\Http\Controllers;

use App\Models\ApiToken;
use App\Models\Attendance;
use App\Models\CalendarEvent;
use App\Models\Leave;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MobileApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'device'   => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password) || $user->status !== 'Active') {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        $plain = Str::random(60);
        ApiToken::create([
            'user_id'     => $user->user_id,
            'token'       => hash('sha256', $plain),
            'device_name' => $request->device,
            'expires_at'  => now()->addYear(),
        ]);

        return response()->json([
            'token' => $plain,
            'user'  => $this->userData($user),
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            ApiToken::where('token', hash('sha256', $token))->delete();
        }

        return response()->json(['message' => 'Logged out.']);
    }

    public function me(Request $request)
    {
        return response()->json($this->userData($request->user()));
    }

    public function checkIn(Request $request)
    {
        $user = $request->user();
        $attendance = Attendance::checkInToday('mobile', [
            'lat' => $request->lat,
            'lng' => $request->lng,
        ], $user);

        return response()->json([
            'message' => 'Checked in.',
            'attendance' => $attendance,
        ]);
    }

    public function checkOut(Request $request)
    {
        $attendance = Attendance::checkOutToday('mobile', $request->user());

        if (!$attendance) {
            return response()->json(['message' => 'You have not checked in today.'], 422);
        }

        return response()->json([
            'message' => 'Checked out.',
            'attendance' => $attendance,
        ]);
    }

    public function attendance(Request $request)
    {
        $records = Attendance::where('staff_id', $request->user()->user_id)
            ->orderByDesc('date')
            ->limit(60)
            ->get();

        return response()->json($records);
    }

    public function leaves(Request $request)
    {
        $leaves = Leave::where('staff_id', $request->user()->user_id)
            ->orderByDesc('id')
            ->get();

        return response()->json($leaves);
    }

    public function storeLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'date_from'  => 'required|date',
            'date_to'    => 'required|date',
            'reason'     => 'required|string',
        ]);

        $user = $request->user();

        $leave = Leave::create([
            'staff_id'      => $user->user_id,
            'employee_name' => $user->name,
            'leave_type'    => $request->leave_type,
            'date_from'     => $request->date_from,
            'date_to'       => $request->date_to,
            'status'        => 'Pending',
            'reason'        => $request->reason,
            'approved_by'   => $user->line_manager,
        ]);

        return response()->json(['message' => 'Leave requested.', 'leave' => $leave], 201);
    }

    public function notifications(Request $request)
    {
        $notifications = Notification::where('recipient', $request->user()->email)
            ->orWhere('recipient', $request->user()->phone_number)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json($notifications);
    }

    public function calendarEvents(Request $request)
    {
        $events = CalendarEvent::orderBy('start_date')->get()->map(function ($event) {
            return [
                'id'          => $event->id,
                'title'       => $event->title,
                'description' => $event->description,
                'start'       => $event->start_date->format('Y-m-d'),
                'end'         => $event->end_date ? $event->end_date->format('Y-m-d') : $event->start_date->format('Y-m-d'),
                'color'       => $event->color,
                'type'        => $event->type,
            ];
        });

        return response()->json($events);
    }

    protected function userData(User $user): array
    {
        return [
            'user_id'     => $user->user_id,
            'name'        => $user->name,
            'email'       => $user->email,
            'role_name'   => $user->role_name,
            'department'  => $user->department,
            'position'    => $user->position,
            'avatar'      => $user->avatar,
            'join_date'   => $user->join_date,
            'qr_token'    => $user->ensureQrToken(),
        ];
    }
}
