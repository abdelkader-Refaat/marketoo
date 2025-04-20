<?php

namespace App\Http\Controllers;

use App\Http\Resources\Api\User\UserCollection;
use App\Http\Resources\Api\User\UserResource;
use App\Models\LandingPage\IntroSlider;
use Illuminate\Http\Request;
use Modules\Posts\Enums\PostPrivacyEnum;
use Modules\Users\App\Models\User;

// Fix namespace

class TestingController extends Controller
{
    //    public private(set) string $version = '8.4';

    public function __invoke(Request $request)
    {
//        return UserCollection::make(User::query()->withWhereRelation('posts', 'privacy',
//            PostPrivacyEnum::Public)->paginate($this->paginateNum()));

        //        return uri(IntroSlider::first()->image);
        //        return UserCollection::make(User::query()->withExists('posts')->whereRelation('posts', 'privacy',
        //            PostPrivacyEnum::Public)->paginate($this->paginateNum()));

        //        return User::whereRelation('posts', 'privacy', PostPrivacyEnum::Public)->get();

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
