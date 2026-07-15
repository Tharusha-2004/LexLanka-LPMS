<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming login request.
     *
     * Flow:
     *  1. Validate format via LoginRequest (email, password required)
     *  2. Attempt authentication with Laravel's Auth guard
     *  3. If credentials are wrong → back with error
     *  4. If the account is suspended → log out immediately and reject
     *  5. Regenerate session to prevent session-fixation attacks
     *  6. Redirect to the dashboard
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        // Attempt authentication
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        // At this point the user is authenticated — check account status
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->status === 'suspended') {
            // Log the user back out immediately
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Your account has been suspended. Please contact the system administrator.']);
        }

        // Regenerate session ID to guard against session-fixation attacks
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy the authenticated session (logout).
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
