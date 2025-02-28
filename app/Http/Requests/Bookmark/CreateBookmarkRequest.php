<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookmark;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponse;

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
            'url' => ['required', 'string', 'url', 'min:8'],
            'title' => ['required', 'string', 'max:512'],
            'favicon' => ['required', 'string'],
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $request = app()->make(HtmxRequest::class);

        if ($request->isHtmxRequest()) {
            $view = View::renderFragment('resources.bookmark.create', 'form', [
                'errors' => $validator->errors(),
            ]);

            $response = with(new HtmxResponse())
                ->addRenderedFragment($view)
                ->reswap('innerHTML')
                ->retarget('#modal-body');

            throw new ValidationException($validator, $response);
        }

        parent::failedValidation($validator);
    }
}
