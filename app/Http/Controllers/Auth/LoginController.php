<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    /** Display the login page */
    public function login()
    {
        return view('auth.login');
    }

    /** Authenticate user and redirect */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $credentials = $request->only('email', 'password') + ['status' => 'Active'];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                Session::put($this->getUserSessionData($user));

                // Update last login
                $user->update(['last_login' => Carbon::now()]);

                AuditLog::record('login', 'Auth', $user->user_id, [], ['email' => $user->email]);

                flash()->success('Login successfully :)');

                return redirect()->intended('home');
            }

            flash()->error('Wrong Username or Password');

            return redirect('login');
        } catch (\Exception $e) {
            \Log::info($e);
            flash()->error('Login failed. Please try again.');

            return redirect()->back();
        }
    }

    /** Prepare User Session Data */
    private function getUserSessionData($user)
    {
        $role = $user->role;
        $modules = $role ? ($role->modules ?? []) : [];

        return [
            'name' => $user->name,
            'email' => $user->email,
            'user_id' => $user->user_id,
            'join_date' => $user->join_date,
            'phone_number' => $user->phone_number,
            'status' => $user->status,
            'role_name' => $user->role_name,
            'avatar' => $user->avatar,
            'position' => $user->position,
            'department' => $user->department,
            'line_manager' => $user->line_manager,
            'second_line_manager' => $user->seconde_line_manager,
            'modules' => $modules,
        ];
    }

    /** Logout and clear session */
    public function logout(Request $request)
    {
        if ($user = Auth::user()) {
            AuditLog::record('logout', 'Auth', $user->user_id, [], ['email' => $user->email]);
        }
        $request->session()->flush();
        Auth::logout();
        flash()->success('Logout successfully :)');

        return redirect('login');
    }
}
