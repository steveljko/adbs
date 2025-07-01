<?php

declare(strict_types=1);

namespace App\Http\Requests\Installer;

use Illuminate\Foundation\Http\FormRequest;

final class DatabaseConnectionRequest extends FormRequest
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
            'db_host' => 'required',
            'db_port' => 'required|numeric',
            'db_database' => 'required',
            'db_username' => 'required',
            'db_password' => 'nullable',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'db_host' => 'database host',
            'db_port' => 'database port',
            'db_database' => 'database name',
            'db_username' => 'database username',
            'db_password' => 'database password',
        ];
    }
}
