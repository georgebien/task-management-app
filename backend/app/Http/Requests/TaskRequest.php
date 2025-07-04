<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Merge authenticated user ID into the validated data.
     * 
     * @param mixed $key
     * @param mixed $default
     * 
     * @return array<string, string>
     */
    public function validated($key = null, $default = null): array
    {
        return array_merge(parent::validated(), [
            'user_id' => auth()->id(),
        ]);
    }
}
