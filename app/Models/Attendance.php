<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\LogsAudit;
use App\Models\User;
use Carbon\Carbon;
use Session;

class Attendance extends Model
{
    use HasFactory, LogsAudit;

    protected $fillable = [
        'staff_id',
        'employee_name',
        'date',
        'check_in',
        'check_out',
        'work_hours',
        'status',
        'remarks',
    ];

    /**
     * Standard work day starting time used to decide "Late" status.
     */
    const OFFICE_START_TIME = '08:30:00';

    /** Resolve the acting user from an explicit instance or the session. */
    protected static function resolveUser(?User $user = null): ?User
    {
        if ($user) {
            return $user;
        }

        if ($staffId = Session::get('user_id')) {
            return User::where('user_id', $staffId)->first();
        }

        return null;
    }

    /** Check-in the given (or current) user for today. */
    public static function checkInToday(string $method = 'manual', ?array $location = null, ?User $user = null): self
    {
        $user    = self::resolveUser($user);
        $staffId = $user?->user_id;
        $name    = $user?->name;
        $today   = Carbon::now()->toDateString();
        $now     = Carbon::now();

        $attendance = self::firstOrNew([
            'staff_id' => $staffId,
            'date'     => $today,
        ]);

        if (empty($attendance->check_in)) {
            $attendance->employee_name = $name;
            $attendance->check_in      = $now->format('H:i:s');
            $attendance->method        = $method;
            $attendance->status        = $now->format('H:i:s') > self::OFFICE_START_TIME ? 'Late' : 'Present';

            if (!empty($location['lat']) && !empty($location['lng'])) {
                $attendance->latitude  = $location['lat'];
                $attendance->longitude = $location['lng'];
            }
            if (!empty($user->biometric_id)) {
                $attendance->biometric_id = $user->biometric_id;
            }

            $attendance->save();
        }

        return $attendance;
    }

    /** Check-out the given (or current) user for today. */
    public static function checkOutToday(string $method = 'manual', ?User $user = null): ?self
    {
        $user    = self::resolveUser($user);
        $staffId = $user?->user_id;
        $today   = Carbon::now()->toDateString();

        $attendance = self::where('staff_id', $staffId)->where('date', $today)->first();

        if ($attendance && empty($attendance->check_out)) {
            $now = Carbon::now();
            $attendance->check_out = $now->format('H:i:s');
            $attendance->method    = $method;

            if (!empty($attendance->check_in)) {
                $in  = Carbon::parse($attendance->check_in);
                $out = Carbon::parse($attendance->check_out);
                $minutes = (int) abs($out->diffInMinutes($in, true));
                $attendance->work_hours = intdiv($minutes, 60) . 'h ' . ($minutes % 60) . 'm';

                // Mark half day if worked less than 4 hours
                if ($minutes < 240 && $attendance->status !== 'Late') {
                    $attendance->status = 'Half Day';
                }
            }

            $attendance->save();
        }

        return $attendance;
    }

    /** Check-in by scanning a QR token (used by the QR attendance flow). */
    public static function checkInByToken(string $token): ?self
    {
        $user = User::where('qr_token', hash('sha256', $token))->first()
            ?? User::where('qr_token', $token)->first();

        if (!$user) {
            return null;
        }

        return self::checkInToday('qr', null, $user);
    }

    /** Check-in via a fingerprint / face-recognition biometric id. */
    public static function checkInByBiometric(string $biometricId): ?self
    {
        $user = User::where('biometric_id', $biometricId)->first();

        if (!$user) {
            return null;
        }

        return self::checkInToday('biometric', null, $user);
    }

    /** Get today's attendance record for the current user, if any. */
    public static function todayFor(?string $staffId = null): ?self
    {
        $staffId = $staffId ?? Session::get('user_id');
        return self::where('staff_id', $staffId)
            ->where('date', Carbon::now()->toDateString())
            ->first();
    }
}
