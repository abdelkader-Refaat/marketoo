<?php

namespace App\Http\Requests\Admin\Auth;

use app\Http\Requests\Api\V1\BaseApiRequest;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->getCredentials();

        if (!Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'identifier' => __('auth.failed'),
            ]);
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
