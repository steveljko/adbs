<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookmark;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

final class ImportBookmarksRequest extends FormRequest
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
            'file' => [
                'required',
                'file',
                'mimes:json',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Please select a file to upload.',
            'file.file' => 'The uploaded item must be a file.',
            'file.mimes' => 'The file must be in JSON format.',
        ];
    }
}
