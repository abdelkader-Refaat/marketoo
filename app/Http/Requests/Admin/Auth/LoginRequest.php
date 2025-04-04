<?php

namespace App\Http\Requests\Admin\Auth;

use app\Http\Requests\Api\V1\BaseApiRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Users\App\Models\User;

class LoginRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login_type' => ['required', 'in:email,phone'],
            'identifier' => ['required', 'string'],
            'country_code' => ['required_if:login_type,phone', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }

    public function prepareForValidation()
    {
        if ($this->login_type === 'phone') {
            $this->merge([
                'phone' => fixPhone($this->phone),
                'country_code' => fixPhone($this->country_code),
            ]);
        }
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->getCredentials();
        $user = User::when($this->login_type === 'phone',
            fn($q) => $q->where('phone', $credentials['phone'])
                ->where('country_code', $credentials['country_code']),
            fn($q) => $q->where('email', $credentials['email'])
        )->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages([
                'identifier' => __('auth.failed'),
            ]);
        }

        // Standard login (no remember token)
        Auth::login($user);

        // Handle "remember me" via Sanctum token expiration
        if ($this->boolean('remember')) {
            $user->createToken('remember-me', expiresAt: now()->addMonth());
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        throw ValidationException::withMessages([
            'identifier' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('identifier')).'|'.$this->ip());
    }

    protected function getCredentials(): array
    {
        if ($this->input('login_type') === 'phone') {
            return [
                'phone' => $this->input('identifier'),
                'country_code' => $this->input('country_code'),
                'password' => $this->input('password'),
            ];
        }

        return [
            'email' => $this->input('identifier'),
            'password' => $this->input('password'),
        ];
    }
}
