<?php

namespace App\Http\Requests;

use App\Enums\TaskStatus;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends TaskRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tasks', 'title')->ignore($this->route('id')),
            ],
            'content' => 'sometimes|nullable|string|max:500',
            'status' => 'required|in:' . implode(',', TaskStatus::getValues()),
            'image' => 'sometimes|nullable|image|max:4096',
        ];
    }
}
