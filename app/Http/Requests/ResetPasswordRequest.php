<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam("email", "string", "Account email", true, "user@gmail.com")]
#[BodyParam("code", "string", "OTP code which had been sent to user email", true, "2-/D3fV")]
#[BodyParam("new_password", "string", "New password", true)]
#[BodyParam("new_password_conirmation", "string", "New password", true)]
class ResetPasswordRequest extends FormRequest
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
            "email" => ["required", "email", "exists:users,email"],
            "code" => ["required"],
            "new_password" => ["required", "confirmed"],
            "new_password_confirmation" => ["required"],
        ];
    }

    //
    public function messages(): array
    {
        return [
            'email.required' => "Please enter your email !!",
            'email.email' => "Please enter a valid email !!",
            'code.required' => "Please enter OTP code  !!",
            'new_password.required' => "Please enter new password !!",
            'new_password.confirmed' => "Password and confirmed password are not equal !!",
            'new_password_confirmation.required' => "Please enter confirmed password !!",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $validator->errors()], 422));
    }
}
