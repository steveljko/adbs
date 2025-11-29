<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookmark;

use App\Rules\UniqueArrayElements;
use Illuminate\Foundation\Http\FormRequest;

final class CreateBookmarkRequest extends FormRequest
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
            'url' => ['required', 'string', 'url:http,https', 'min:8'],
            'title' => ['required', 'string', 'max:512'],
            'tags' => ['array', 'distinct', new UniqueArrayElements()],
        ];
    }
}
