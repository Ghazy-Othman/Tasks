<?php

namespace App\Http\Requests\API\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam("name", "string", "User name", true, "user user")]
#[BodyParam("email", "string", "Account email", true, "user@gmail.com")]
#[BodyParam("password", "string", "Account password", true)]
#[BodyParam("image", "file", "Profile image", true,)]
class RegisterRequest extends FormRequest
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
            "name" => ['required', 'string'],
            "email" => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string'],
            "image" => ["sometimes", "mimes:jpeg,png,jpg,svg"],
        ];
    }
}
