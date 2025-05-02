<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Http\Requests\OTPCodeRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\CustomResponse;
use App\Models\User;
use App\Notifications\OTPRequestNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Hash;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Tymon\JWTAuth\Facades\JWTAuth;

#[Group("Users", "Users actions")]
#[Subgroup("Auth", "Register,login,logout,reset password and refresh tokens")]
class AuthController extends Controller
{
    /**
     * Login
     * 
     * @param \App\Http\Requests\API\Auth\LoginRequest $request
     * @return CustomResponse
     */
    public function login(LoginRequest $request): CustomResponse
    {
        $data = $request->validated();

        if (! $token = auth("api")->attempt($data)) {
            return CustomResponse::unauthorized("Wrong email or password !!");
        }

        $response = [
            'msg' => 'User logged in successfully',
            'token' => $token,
        ];

        return CustomResponse::ok($response);
    }

    /**
     * Register
     * 
     * @param \App\Http\Requests\API\Auth\RegisterRequest $request
     * @return CustomResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create(attributes: $data);
        $token = JWTAuth::fromUser($user);

        $response = [
            'msg' => 'User registered successfully',
            'token' => $token,
            'user' => new UserResource($user),
        ];

        return CustomResponse::created($response);
    }


    /**
     * Logout
     * 
     * @return CustomResponse
     */
    #[Authenticated]
    public function logout(): CustomResponse
    {
        auth('api')->logout();

        return CustomResponse::ok('User logged out successfully');
    }


    /**
     * Refresh Token
     * 
     * @return CustomResponse
     */
    #[Authenticated]
    public function refreshToken(): CustomResponse
    {
        $new_token = auth('api')->refresh();

        $response = [
            'msg' => 'Token refreshed successfully',
            'token' => $new_token,
        ];

        return CustomResponse::ok($response);
    }

    /**
     * Request OTP code
     * 
     * @param \App\Http\Requests\OTPCodeRequest $request
     * @return CustomResponse
     */
    public function requestOTPCode(OTPCodeRequest $request): CustomResponse
    {
        //
        $validData = $request->validated();

        $user = User::where('email', $validData['email'])->first();

        $user->notify(new OTPRequestNotification());

        return CustomResponse::ok(['msg' => 'OTP code has been sent successfully ']);
    }

    /**
     * Reset Password
     * 
     * @param \App\Http\Requests\ResetPasswordRequest $request
     * @return CustomResponse
     */
    public function resetPassword(ResetPasswordRequest $request): CustomResponse
    {
        //
        $validData = $request->validated();
        // 
        $result = (new OTP)->validate($validData['email'], $validData['code']);
        //
        if ($result->status) {
            $user = User::where('email', $validData['email'])->first();
            $user->password = Hash::make($validData['new_password']);
            $user->save();

            return CustomResponse::ok(["msg" => "Password updated successfully"]);
        }

        // Failed to update password (OTP expired or not valid)
        return CustomResponse::badRequest(errorMessage: $result->message);
    }
}
