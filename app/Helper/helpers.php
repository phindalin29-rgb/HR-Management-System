<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/** for side bar menu active */
function set_active($route)
{
    if (is_array($route)) {
        return in_array(Request::path(), $route) ? 'active' : '';
    }

    return Request::path() == $route ? 'active' : '';
}

/** Check if the logged-in user has the given module in their session or role. */
function has_module(string $module): bool
{
    $user = Auth::user();

    if (! $user) {
        return false;
    }

    $modules = Session::get('modules');

    if (! empty($modules)) {
        return in_array('*', $modules, true) || in_array($module, $modules, true);
    }

    return $user->hasAnyModule([$module]);
}

/** Check if the logged-in user has any of the given modules in their session or role. */
function has_any_module(array $modules): bool
{
    $user = Auth::user();

    if (! $user) {
        return false;
    }

    $sessionModules = Session::get('modules');

    if (! empty($sessionModules)) {
        return (bool) count(array_intersect($modules, $sessionModules));
    }

    return $user->hasAnyModule($modules);
}
