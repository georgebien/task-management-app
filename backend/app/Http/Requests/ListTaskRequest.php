<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;

class ListTaskRequest extends TaskRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'statuses' => 'sometimes|array',
            'statuses.*' => 'sometimes|in:' . implode(',', TaskStatus::getValues()),
            'only_deleted' => 'sometimes|boolean|in:0,1',
            'search' => 'sometimes|string|max:255',
            'order_by' => 'sometimes|in:title,created_at',
            'order_dir' => 'sometimes|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
        ];
    }

    public function pagination(): array
    {
        return [
            'page' => $this->input('page', 1),
            'per_page' => $this->input('per_page', 10),
        ];
    }
}
