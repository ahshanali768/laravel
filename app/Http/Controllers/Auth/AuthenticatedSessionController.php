<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request)
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = \Illuminate\Support\Facades\Auth::user();

    // Role-based redirect
    if ($user && method_exists($user, 'hasRole') && is_callable([$user, 'hasRole'])) {
        if ($user->hasRole('Admin')) {
            return redirect()->intended('/admin/dashboard');
        } elseif ($user->hasRole('agent')) {
            return redirect()->intended('/agent/dashboard');
        } elseif ($user->hasRole('verifier')) {
            return redirect()->intended('/verifier/dashboard');
        }
        // fallback for users with hasRole but no matching role
        return redirect()->intended('/dashboard');
    } else {
        // If user does not have hasRole, fallback to dashboard
        return redirect()->intended('/dashboard');
    }
}

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
