<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Hash;
use DB;

class RegisterController extends Controller
{
    /** Show the registration page */
    public function showRegistrationForm()
    {
        $roles = Role::orderBy('name')->get();
        return view('auth.register', compact('roles'));
    }

    /** Store New User */
    public function register(Request $request)
    {
        try {
            $users = new User();
            return $users->saveNewuser($request);
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to Create Account. Please try again.');
            return redirect()->back();
        }
    }
}