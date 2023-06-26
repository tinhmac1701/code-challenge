<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Repositories\AuthRepository;
use Modules\Common\Traits\ResponseTrait;


class AuthController extends Controller
{
    use ResponseTrait;
    public $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Summary of login
     * @param \Modules\Auth\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            if ($this->guard()->attempt($credentials)) {
                $user = User::where('email', $request->email)->first();
                $token = $user->createToken('authToken')->plainTextToken;
                $data =  $this->respondWithToken($token);
            } else {
                return $this->responseError(null, __('auth::messages.login.fail'), Response::HTTP_UNAUTHORIZED);
            }

            return $this->responseSuccess($data, __('auth::messages.login.success'));
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of logout
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->responseSuccess(null, __('auth::messages.logout.success'));
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Summary of register
     * @param \Modules\Auth\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $requestData = $request->only('name', 'email', 'password', 'password_confirmation', 'is_admin');
            $user = $this->authRepository->register($requestData);
            if ($user) {
                if ($this->guard()->attempt($requestData)) {
                    $token = $user->createToken('authToken')->plainTextToken;
                    $data =  $this->respondWithToken($token);
                    return $this->responseSuccess($data, __('auth::messages.register.success'), Response::HTTP_OK);
                }
            }
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): array
    {
        $data = [[
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => $this->guard()->factory()->getTTL() * 60, //only for jwt
            'user' => $this->guard()->user()
        ]];
        return $data[0];
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard(): \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
    {
        return Auth::guard();
    }
}
