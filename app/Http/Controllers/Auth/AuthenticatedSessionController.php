<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
            'defaultCountryCode' => '+966', // Set default country code for Saudi Arabia
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
//        dd($request->validated());
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('site.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            // Sanctum token revocation
            if (method_exists($user, 'currentAccessToken')) {
                $user->currentAccessToken()?->delete();
            }

            // Web session cleanup
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('status', 'Logged out successfully');
        } catch (\Exception $e) {
            \Log::error('Logout error', ['error' => $e->getMessage()]);
            return redirect('/')->with('error', 'Logout failed. Please try again.');
        }
    }
}
