<?php

namespace App\Http\Requests\Folders;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FolderStoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'icon_emoji' => ['sometimes', 'nullable', 'string', 'max:16'],
            'color' => ['sometimes', 'nullable', 'string', 'max:16', 'regex:/^#([0-9a-fA-F]{6})$/'],
        ];
    }
}
