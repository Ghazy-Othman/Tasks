<?php

namespace App\Http\Requests\API\Tasks;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam("title", "string", "Task title", true, "new taks")]
#[BodyParam("content", "text", "Task content", true, "This is task content (description)")]
#[BodyParam("priority", "integer", "Task priority", true, 5)]
class NewTaskRequest extends FormRequest
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
            //
            "title" => ["required", "string", "max:100"],
            "content" => ["required", "string"],
            "priority" => ["required", "integer"],
        ];
    }
}
