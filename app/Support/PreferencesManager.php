<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use InvalidArgumentException;
use Nette\Schema\ValidationException;

final class PreferencesManager
{
    private User $user;

    /**
     * name:
     *  - type
     *  - default
     *  - allowed_values
     *  - category (can be blank)
     *  - description
     */
    // TODO: move allowed preferences into config dir.
    private static array $allowed = [
        'view_type' => [
            'type' => 'string',
            'default' => 'card',
            'allowed_values' => ['card', 'list', 'low_performance'],
            'category' => 'view',
            'description' => 'Default view type for displaying content on homepage.',
        ],
        'disable_view_switch' => [
            'type' => 'boolean',
            'default' => false,
            'category' => 'view',
            'description' => 'Hide the floating view switch on all pages.',
        ],
    ];

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public static function for(User $user): self
    {
        return new self(user: $user);
    }

    /**
     * Get preference value with type casting and fallback to default
     */
    public function get(string $key, $fallback = null)
    {
        $this->validatePreferenceKey($key);

        $preference = $this->user->preferences()->where('key', $key)->first();

        if (! $preference) {
            return $fallback ?? $this->getDefaultValue($key);
        }

        return $this->castValue($key, $preference->value);
    }

    /**
     * Set preference with validation
     */
    public function set(string $key, $value): self
    {
        $this->validatePreferenceKey($key);
        $this->validatePreferenceValue($key, $value);

        $stringValue = $this->convertToString($value);

        $this->user->preferences()->updateOrCreate(
            ['key' => $key],
            ['value' => $stringValue]
        );

        return $this;
    }

    /**
     * Cast value to appropriate type
     */
    private function castValue(string $key, string $value)
    {
        $type = self::$allowed[$key]['type'];

        return match ($type) {
            'boolean' => in_array(mb_strtolower($value), ['true', '1']),
            'integer' => (int) $value,
            'float', 'double' => (float) $value,
            'array' => json_decode($value, true) ?? [],
            default => $value,
        };
    }

    /**
     * Get default value for a preference
     */
    private function getDefaultValue(string $key)
    {
        return self::$allowed[$key]['default'] ?? null;
    }

    /**
     * Convert value to string for database save
     */
    private function convertToString($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }

    /**
     * Validate preference key
     */
    private function validatePreferenceKey(string $key): void
    {
        if (! isset(self::$allowed[$key])) {
            throw new InvalidArgumentException("Preference key '{$key}' is not allowed.");
        }
    }

    /**
     * Validate preference value
     */
    private function validatePreferenceValue(string $key, $value): void
    {
        $allowedPreferences = self::$allowed;
        $config = $allowedPreferences[$key];

        $expectedType = $config['type'];
        $actualType = gettype($value);

        if ($expectedType === 'boolean' && is_string($value)) {
            if (! in_array(mb_strtolower($value), ['true', 'false', '1', '0'])) {
                throw new ValidationException("Preference '{$key}' must be a boolean value.");
            }
        } elseif ($expectedType === 'integer' && is_string($value)) {
            if (! is_numeric($value) || ! is_int($value + 0)) {
                throw new ValidationException("Preference '{$key}' must be an integer value.");
            }
        } elseif ($expectedType !== $actualType && ! ($expectedType === 'integer' && $actualType === 'double')) {
            throw new ValidationException("Preference '{$key}' must be of type {$expectedType}, {$actualType} given.");
        }

        $castedValue = $this->castValue($key, $this->convertToString($value));

        if (isset($config['allowed_values']) && ! in_array($castedValue, $config['allowed_values'])) {
            $allowedValues = implode(', ', $config['allowed_values']);
            throw new ValidationException("Preference '{$key}' must be one of: {$allowedValues}");
        }

        if ($expectedType === 'integer') {
            if (isset($config['min']) && $castedValue < $config['min']) {
                throw new ValidationException("Preference '{$key}' must be at least {$config['min']}");
            }

            if (isset($config['max']) && $castedValue > $config['max']) {
                throw new ValidationException("Preference '{$key}' must be at most {$config['max']}");
            }
        }
    }
}
