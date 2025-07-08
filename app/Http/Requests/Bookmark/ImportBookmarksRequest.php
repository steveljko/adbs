<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookmark;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponse;

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

    public function failedValidation(Validator $validator): void
    {
        $request = app()->make(HtmxRequest::class);

        if ($request->isHtmxRequest()) {
            $view = View::renderFragment('partials.bookmark.import-export.export', 'form', [
                'errors' => $validator->errors(),
            ]);

            $response = with(new HtmxResponse())
                ->addRenderedFragment($view)
                ->reswap('innerHTML')
                ->retarget('form');

            throw new ValidationException($validator, $response);
        }

        parent::failedValidation($validator);
    }
}
