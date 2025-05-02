<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Knuckles\Scribe\Attributes\QueryParam;

#[QueryParam("priority", 'integer', "Get tasks with specific priority", false, 5)]
#[QueryParam("priorityFrom", 'integer', "Get tasks with priorities greater than or equal to this value", false, 1)]
#[QueryParam("priorityTo", 'integer', "Get tasks with priorities smaller than or equal to this value", false, 10)]
#[QueryParam("dateFrom", 'date', "Get tasks with date later than or equal to this date", false, "20/5/2025")]
#[QueryParam("dateTo", 'date', "Get tasks with priorities earlier than or equal to this date", false, "1/7/2025")]
#[QueryParam("sortBy", 'enum', "Sort result by this value. Accepted values : priority , date", false, "priority")]
#[QueryParam("sortOrder", 'enum', "Sort result type. Accepted values : asc , desc", false, "asc")]
class TasksListRequest extends FormRequest
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
            "priority" => "numeric",
            "priorityFrom" => "numeric",
            "priorityTo" => "numeric",
            "dateFrom" => "date",
            "dateTo" => "date",
            "sortBy" => Rule::in(['priority', 'date']),
            "sortOrder" => Rule::in(['asc', 'desc'])
        ];
    }
}
