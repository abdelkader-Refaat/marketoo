<?php

namespace App\Http\Controllers;

use App\Http\Resources\Api\User\UserResource;
use Illuminate\Http\Request;
use Modules\Users\App\Models\User;

// Fix namespace

class TestingController extends Controller
{
    //    public private(set) string $version = '8.4';

    public function __invoke(Request $request)
    {
//        $name = 'abdelkader';
//        $message = <<<TEXT
//        Hello $name,
//        Welcome to
//        our platform!
//        TEXT;
//        return $message;
        //        return User::query()->findOrFail(21)->toResource(UserResource::class);
    }
}
