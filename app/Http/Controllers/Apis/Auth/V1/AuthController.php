<?php
namespace App\Http\Controllers\Apis\Auth\V1;


use App\Http\Resources\RegisterResource;
use App\Models\User;
use App\Http\Requests\Auth\Apis\V1\RegisterRequest;
use Illuminate\Http\JsonResponse;
class AuthController
{
    public function register(RegisterRequest $request)
    {
        try {
        $user = User::create($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(RegisterResource::make($user, $token), 201)->header('Authorization', "Bearer {$token}");
        }catch(\Exception $exception){
            return response()->json(['error'=>$exception->getMessage()]);
        }
    }


}
