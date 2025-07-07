<?php

namespace App\Http\Requests;

class StoreTaskRequest extends TaskRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|unique:tasks,title|max:100',
            'content' => 'sometimes|nullable|string|max:500',
            'image' => 'sometimes|nullable|image|max:4096',
            'is_draft' => 'required|boolean|in:0,1',
        ];
    }
}
