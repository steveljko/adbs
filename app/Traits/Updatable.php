<?php

declare(strict_types=1);

namespace App\Traits;

trait Updatable
{
    private array $additionalFields = [];

    /**
     * Generate update message based on changed fields
     */
    public function getUpdateMessage(array $displayNames = []): string
    {
        $changedFields = array_keys($this->getDirty());
        $allChangedFields = array_merge($changedFields, $this->additionalFields);

        $changes = array_map(
            fn ($field) => $displayNames[$field] ?? $field,
            $allChangedFields
        );

        return match (count($changes)) {
            1 => "{$this->getEntityName()} {$changes[0]} has been updated.",
            2 => "{$this->getEntityName()} {$changes[0]} and {$changes[1]} have been updated.",
            default => $this->getEntityName().' '.implode(', ', array_slice($changes, 0, -1)).' and '.end($changes).' have been updated.'
        };
    }

    /*
     * Provide additional fields for genereating update message
     */
    public function withAdditionalFields(array $additionalFields)
    {
        $this->additionalFields = $additionalFields;
    }

    /**
     * Fill, save and return update message
     */
    public function updateAndRespond(array $data, array $overrideName = [], array $additionalFields = []): array
    {
        $this->fill($data);

        $isChanged = $this->isDirty() || ! empty($this->additionalFields);

        if (! $isChanged) {
            return [
                'isChanged' => $isChanged,
                'message' => "No changes were made to the {$this->getEntityName()}.",
            ];
        }

        $message = $this->getUpdateMessage($overrideName, $additionalFields);
        $this->save();

        return [
            'isChanged' => $isChanged,
            'message' => $message,
        ];
    }

    /**
     * Get the entity name for the model
     */
    protected function getEntityName(): string
    {
        return class_basename($this);
    }
}
