<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Mauricius\LaravelHtmx\Http\HtmxRequest;
use Mauricius\LaravelHtmx\Http\HtmxResponse;

final class LoginRequest extends FormRequest
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
            'email' => ['required', 'email', 'min:8', 'max:255'],
            'password' => ['required', 'min:8', 'max:255'],
        ];
    }

    public function failedValidation(Validator $validator): void
    {
        $request = app()->make(HtmxRequest::class);

        if ($request->isHtmxRequest()) {
            $view = View::renderFragment('resources.auth.login', 'form', [
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
