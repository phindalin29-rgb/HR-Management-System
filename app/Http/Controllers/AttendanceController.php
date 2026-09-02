<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Session;
use DB;

class AttendanceController extends Controller
{
    /** Attendance Admin - monthly matrix for all employees */
    public function attendanceIndex(Request $request)
    {
        $month = (int) ($request->month ?? Carbon::now()->month);
        $year  = (int) ($request->year ?? Carbon::now()->year);

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;

        $employees = User::orderBy('name')->get();

        $records = Attendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy('staff_id');

        return view('employees.attendance', compact('employees', 'records', 'month', 'year', 'daysInMonth'));
    }

    /** Attendance Employee - self service check-in/out + personal history */
    public function attendanceEmployee(Request $request)
    {
        $staffId = Session::get('user_id');
        $month   = (int) ($request->month ?? Carbon::now()->month);
        $year    = (int) ($request->year ?? Carbon::now()->year);

        $today = Attendance::todayFor($staffId);

        $history = Attendance::where('staff_id', $staffId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderByDesc('date')
            ->get();

        $summary = [
            'present'  => $history->whereIn('status', ['Present', 'Late'])->count(),
            'late'     => $history->where('status', 'Late')->count(),
            'half_day' => $history->where('status', 'Half Day')->count(),
        ];

        return view('employees.attendanceemployee', compact('today', 'history', 'summary', 'month', 'year'));
    }

    /** Check-in the currently logged in employee for today */
    public function checkIn(Request $request)
    {
        try {
            $method = $request->input('method', 'manual');
            Attendance::checkInToday($method);
            flash()->success('Checked in successfully :)');
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to check in :)');
        }
        return redirect()->back();
    }

    /** Check-out the currently logged in employee for today */
    public function checkOut(Request $request)
    {
        try {
            $method = $request->input('method', 'manual');
            $attendance = Attendance::checkOutToday($method);
            if (!$attendance) {
                flash()->error('You have not checked in today :)');
            } else {
                flash()->success('Checked out successfully :)');
            }
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to check out :)');
        }
        return redirect()->back();
    }

    /** QR Code attendance page for the current employee */
    public function qrCode()
    {
        $user = User::where('user_id', Session::get('user_id'))->first();
        $token = $user ? $user->ensureQrToken() : null;
        $today = Attendance::todayFor(Session::get('user_id'));

        $scanUrl = $token ? url('/attendance/qr/scan/' . $token) : null;

        return view('employees.qrcode', compact('user', 'token', 'scanUrl', 'today'));
    }

    /** Record attendance by scanning a QR token (kiosk / mobile camera) */
    public function qrScan(string $token)
    {
        try {
            $attendance = Attendance::checkInByToken($token);
            if (!$attendance) {
                return view('employees.qrscan-result', [
                    'success' => false,
                    'message' => 'Invalid QR code.',
                ]);
            }
            return view('employees.qrscan-result', [
                'success'  => true,
                'message'  => 'Checked in successfully.',
                'employee' => $attendance->employee_name,
                'time'     => $attendance->check_in,
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return view('employees.qrscan-result', [
                'success' => false,
                'message' => 'Failed to check in.',
            ]);
        }
    }

    /** Check-in via fingerprint / face recognition biometric id */
    public function biometricCheckIn(Request $request)
    {
        $request->validate([
            'biometric_id' => 'required|string',
        ]);

        try {
            $attendance = Attendance::checkInByBiometric($request->biometric_id);
            if (!$attendance) {
                return response()->json(['success' => false, 'message' => 'Biometric not recognised.'], 404);
            }
            return response()->json([
                'success'     => true,
                'message'     => 'Checked in via biometric.',
                'employee'    => $attendance->employee_name,
                'check_in'    => $attendance->check_in,
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['success' => false, 'message' => 'Failed to check in.'], 500);
        }
    }
}
