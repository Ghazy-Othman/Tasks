<?php

namespace App\Http\Requests\API\Tasks;

use Illuminate\Foundation\Http\FormRequest;
use Knuckles\Scribe\Attributes\BodyParam;

#[BodyParam("title", "string", "Task title", false, "new taks")]
#[BodyParam("content", "text", "Task content", false, "This is task content (description)")]
#[BodyParam("priority", "integer", "Task priority", false, 5)]
class UpdateTaskRequest extends FormRequest
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
            "title" => ["sometimes", "string"],
            "content" => ["sometimes", "string"],
            "priority" => ["sometimes", "integer"],
        ];
    }
}
