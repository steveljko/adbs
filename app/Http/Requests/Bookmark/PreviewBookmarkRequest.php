<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookmark;

use Closure;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponse;

final class PreviewBookmarkRequest extends FormRequest
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
        ];
    }

    /**
     * @return array<int,Closure(Validator): <missing>>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                // Check if website is reachable.
                try {
                    Http::get($this->url);
                } catch (ConnectionException $e) {
                    $validator->errors()->add(
                        'url',
                        __('validation.website_unreachable')
                    );
                }
            },
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
