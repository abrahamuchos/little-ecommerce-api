<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param RegisterUserRequest $request
     *
     * @return UserResource | JsonResponse
     */
    public function register(RegisterUserRequest $request): UserResource | JsonResponse
    {
        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address
            ]);
            $user->roles()->attach($request->role);
        }catch (QueryException|\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }

        $token = $this->_createToken($user);

        return (new UserResource($user->load('roles')))->additional(['auth_token' => $token]);
    }

    /**
     * @param LoginUserRequest $request
     *
     * @return UserResource|JsonResponse
     */
    public function login(LoginUserRequest $request): UserResource | JsonResponse
    {
        if(!Auth::attempt($request->only(['email', 'password']))){
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $this->_createToken($user);

        return (new UserResource($user->load('roles')))->additional(['auth_token' => $token]);
    }

    /**
     * @param Request $request - Token auth (to get user)
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'You have successfully logged out the token was deleted'
        ]);
    }

    /**
     * @param User $user
     *
     * @return string
     */
    private function _createToken(User $user): string
    {
        return $user->createToken('auth_token')->plainTextToken;
    }


}
