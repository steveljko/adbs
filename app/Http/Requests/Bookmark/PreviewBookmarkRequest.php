<?php

declare(strict_types=1);

namespace App\Http\Requests\Bookmark;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

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
                if ($this->url !== null && ! $validator->errors()->has('url')) {
                    try {
                        Http::timeout(5)->get($this->url);
                    } catch (ConnectionException $e) {
                        $validator->errors()->add(
                            'url',
                            __('validation.website_unreachable')
                        );
                    }
                }
            },
        ];
    }

    /**
     * Handle failed validation and return validation errors as a JSON response.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
