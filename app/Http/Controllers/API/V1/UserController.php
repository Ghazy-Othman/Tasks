<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Http\Responses\CustomResponse;
use App\Models\User;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Subgroup;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group("Users")]
#[Subgroup("Personal", "Get,update and delete user")]
class UserController extends Controller
{
    /**
     * Show
     * 
     * @param \App\Models\User $user
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    public function show($user_id): CustomResponse
    {
        $user = User::findOrFail($user_id);

        return CustomResponse::ok(data: new UserResource(resource: $user));
    }

    /**
     * Update
     * 
     * @param \App\Http\Requests\API\User\UpdateUserRequest $request
     * @param \App\Models\User $user
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    public function update(UpdateUserRequest $request, $user_id): CustomResponse
    {
        $user = User::findOrFail($user_id);
        $data = $request->validated();

        if (isset($data['name'])) {
            $user->update(['name' => $data['name']]);
        }

        if (isset($data['no_image'])) {
            $user->deleteProfileImage();
        } else if (isset($data['image'])) {
            $user->image = $data['image'];
            $user->save();
        }

        $response = [
            'user' => new UserResource($user)
        ];

        return CustomResponse::ok($response);
    }

    /**
     * Delete
     * 
     * @param \App\Models\User $user
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    public function destroy($user_id): CustomResponse
    {
        $user = User::findOrFail($user_id);
        $user->deleteProfileImage();
        $user->delete();

        auth(guard: 'api')->logout();

        return CustomResponse::deleted();
    }
}
