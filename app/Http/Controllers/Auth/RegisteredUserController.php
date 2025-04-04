<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\User\Individual\RegisterRequest;
use Inertia\Inertia;
use Inertia\Response;
use Modules\Users\App\Models\User;

class RegisteredUserController extends Controller
{

    public function store(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        if ($request->hasFile('avatar')) {
            $user->avatar_path = $request->file('avatar')->store('avatars');
            $user->save();
        }

        auth()->login($user);

        return redirect()->route('site.dashboard');
    }

    public function create()
    {
        return Inertia::render('auth/register');
    }
}

