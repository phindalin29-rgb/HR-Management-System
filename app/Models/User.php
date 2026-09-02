<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;
use DB;
use Illuminate\Support\Str;
use App\Models\Role;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users'; // Specify the table name if it's not pluralized

    protected $fillable = [
        'last_login', // Ensure this is included
        'role_id',
        'role_name',
        'biometric_id',
        'qr_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /** Whether this user's role is permitted to access the given module. */
    public function hasModule(string $module): bool
    {
        if (!$this->role) {
            return true; // no assigned role -> legacy/unrestricted (avoid lockout)
        }

        return $this->role->hasModule($module);
    }

    /** Whether this user's role is permitted to access any of the given modules. */
    public function hasAnyModule(array $modules): bool
    {
        foreach ($modules as $module) {
            if ($this->hasModule($module)) {
                return true;
            }
        }

        return false;
    }

    /** Generate a unique QR token used for QR-code attendance. */
    public function ensureQrToken(): string
    {
        if (empty($this->qr_token)) {
            $this->qr_token = hash('sha256', $this->user_id . '|' . Str::random(40));
            $this->saveQuietly();
        }
        return $this->qr_token;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** generate id */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $latestUser = self::orderBy('user_id', 'desc')->first();
            $nextID = $latestUser ? intval(substr($latestUser->user_id, 3)) + 1 : 1;
            $model->user_id = 'KH-' . sprintf("%04d", $nextID);

            // Ensure the user_id is unique
            while (self::where('user_id', $model->user_id)->exists()) {
                $nextID++;
                $model->user_id = 'KH-' . sprintf("%04d", $nextID);
            }
        });
    }

    /** Insert New Users */
    public function saveNewuser(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'role_id'   => 'required|exists:roles,id',
            'password'  => 'required|string|min:8|confirmed',
        ]);
        
        try {
            $role = Role::findOrFail($request->role_id);

            $todayDate = Carbon::now()->toDayDateTimeString();
            $save             = new User;
            $save->name       = $request->name;
            $save->avatar     = $request->image;
            $save->email      = $request->email;
            $save->join_date  = $todayDate;
            $save->role_id    = $role->id;
            $save->role_name  = $role->name;
            $save->status     = 'Active';
            $save->password   = Hash::make($request->password);
            $save->save();

            flash()->success('Account created successfully :)');
            return redirect('login');
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to Create Account. Please try again.');
            return redirect()->back();
        }
    }

}
