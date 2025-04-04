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
            Auth::guard('web')->logout();
            $request->session()->flush();
            $request->session()->regenerate();
            $request->session()->regenerateToken();
            $cookie = Cookie::forget(Auth::getRecallerName());
            return redirect('/')
                ->withCookie($cookie)
                ->with('status', 'You have been successfully logged out.');
        } catch (\Exception $e) {
            // Log detailed error for debugging
            \Log::error('Logout failed for user ID: '.optional($user)->id, [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            return redirect('/')
                ->with('error', 'We encountered an issue logging you out. Please try again.');
        }
    }
}
