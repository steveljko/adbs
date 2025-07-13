<?php

declare(strict_types=1);

namespace App\Http\Requests\Installer;

use App\Support\Database\DatabaseConnectionStrategyFactory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class DatabaseConnectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $driver = $this->input('db_driver');

        if (! $driver || ! in_array($driver, DatabaseConnectionStrategyFactory::getSupportedDrivers())) {
            return [
                'db_driver' => ['required', Rule::in(DatabaseConnectionStrategyFactory::getSupportedDrivers())],
            ];
        }

        $strategy = DatabaseConnectionStrategyFactory::create($driver);
        $requiredFields = $strategy->getRequiredFields();

        $rules = [
            'db_driver' => ['required', Rule::in(DatabaseConnectionStrategyFactory::getSupportedDrivers())],
        ];

        foreach ($requiredFields as $field) {
            $rules[$field] = ['required', 'string'];
        }

        if (in_array($driver, ['pgsql', 'mysql'])) {
            $rules['db_port'] = ['nullable', 'integer', 'min:1', 'max:65535'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'db_driver.required' => 'Database driver is required.',
            'db_driver.in' => 'Invalid database driver selected.',
            'db_host.required' => 'Database host is required.',
            'db_database.required' => 'Database name is required.',
            'db_username.required' => 'Database username is required.',
            'db_password.required' => 'Database password is required.',
            'db_port.integer' => 'Database port must be a valid integer.',
            'db_port.min' => 'Database port must be at least 1.',
            'db_port.max' => 'Database port must not exceed 65535.',
        ];
    }
}
