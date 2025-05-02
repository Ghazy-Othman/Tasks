<?php

namespace App\Http\Requests\API\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam("name", 'string', "User new name", false)]
#[BodyParam("image", 'file', "User new image. If no_image param exists, this will be ignored.", false)]
#[BodyParam("no_image", 'boolean', "True to delete user profile image", false , true)]
class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["sometimes", "string"],
            "image" => ["sometimes", "mimes:jpeg,png,jpg,svg"],
            "no_image" => ["sometimes", Rule::in(values: [true])],
        ];
    }
}
