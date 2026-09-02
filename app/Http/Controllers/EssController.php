<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;

class EssController extends Controller
{
    public function index()
    {
        $staffId = Session::get('user_id');
        $user    = User::where('user_id', $staffId)->first();

        $today = Attendance::todayFor($staffId);

        $leaves = Leave::where('staff_id', $staffId)
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $leaveBalance = Leave::where('staff_id', $staffId)
            ->where('status', 'Approved')
            ->sum('number_of_day');

        $notifications = Notification::where('recipient', $user->email ?? '')
            ->orWhere('recipient', $user->phone_number ?? '')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $summary = [
            'present'  => Attendance::where('staff_id', $staffId)
                ->whereMonth('date', Carbon::now()->month)
                ->whereIn('status', ['Present', 'Late'])->count(),
            'late'     => Attendance::where('staff_id', $staffId)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'Late')->count(),
            'half_day' => Attendance::where('staff_id', $staffId)
                ->whereMonth('date', Carbon::now()->month)
                ->where('status', 'Half Day')->count(),
        ];

        return view('ess.index', compact('user', 'today', 'leaves', 'leaveBalance', 'notifications', 'summary'));
    }
}
